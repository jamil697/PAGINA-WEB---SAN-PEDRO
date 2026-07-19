/**
 * escaneo.js
 * ---------------------------------------------------------------------
 * Módulo de Visión Artificial — Iglesia San Pedro de Huánuco
 * ---------------------------------------------------------------------
 * 1) Activa la cámara TRASERA del dispositivo móvil.
 * 2) Carga un modelo de clasificación de imágenes entrenado con
 *    Teachable Machine (formato TensorFlow.js).
 * 3) Ejecuta predicciones en un bucle sobre el video en vivo.
 * 4) Cuando la confianza supera el umbral configurado, consulta
 *    de forma asíncrona la API de Laravel para obtener la ficha
 *    histórica del bien cultural detectado y la pinta en pantalla,
 *    sin recargar la página.
 * ---------------------------------------------------------------------
 */

(function () {
    'use strict';

    // Referencias al DOM
    const videoEl = document.getElementById('video-camara');
    const recuadroGuia = document.getElementById('recuadro-guia');
    const canvasCaptura = document.getElementById('canvas-captura');
    const estadoDeteccion = document.getElementById('estado-deteccion');
    const panelResultado = document.getElementById('panel-resultado');
    const resultadoNombre = document.getElementById('resultado-nombre');
    const resultadoAutorSiglo = document.getElementById('resultado-autor-siglo');
    const resultadoResena = document.getElementById('resultado-resena');
    const resultadoIconografiaWrapper = document.getElementById('resultado-iconografia-wrapper');
    const resultadoIconografia = document.getElementById('resultado-iconografia');

    // Config inyectada desde escaneo.blade.php
    const { urlModelo, urlApiBusqueda, umbralConfianza } = window.CONFIG_IA;

    let modelo = null;
    let etiquetaActualMostrada = null;
    let bloqueadoPorConsulta = false;
    let animacionEnCurso = null;

    /**
     * Inicializa la cámara trasera del dispositivo mediante
     * navigator.mediaDevices.getUserMedia.
     */
    async function iniciarCamara() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' } }, // cámara trasera
                audio: false,
            });

            videoEl.srcObject = stream;

            return new Promise((resolve) => {
                videoEl.onloadedmetadata = () => {
                    videoEl.play();
                    ajustarRecuadroGuia();
                    resolve();
                };
            });
        } catch (error) {
            mostrarEstado('No se pudo acceder a la cámara. Verifica los permisos del navegador.', true);
            console.error('Error al acceder a la cámara:', error);
            throw error;
        }
    }

    /**
     * Dibuja un recuadro guía simple sobre el canvas superpuesto al video,
     * para orientar al usuario sobre dónde centrar el bien cultural.
     */
    function ajustarRecuadroGuia() {
        recuadroGuia.width = videoEl.videoWidth;
        recuadroGuia.height = videoEl.videoHeight;

        const ctx = recuadroGuia.getContext('2d');
        const margenX = recuadroGuia.width * 0.15;
        const margenY = recuadroGuia.height * 0.15;

        ctx.strokeStyle = '#ffffffaa';
        ctx.lineWidth = 4;
        ctx.strokeRect(
            margenX,
            margenY,
            recuadroGuia.width - margenX * 2,
            recuadroGuia.height - margenY * 2
        );
    }

    /**
     * Carga el modelo de clasificación de imágenes exportado
     * desde Teachable Machine (TensorFlow.js).
     */
    async function cargarModeloIA() {
        const urlJson = urlModelo + 'model.json';
        const urlMetadata = urlModelo + 'metadata.json';

        modelo = await tmImage.load(urlJson, urlMetadata);
        mostrarEstado('Modelo cargado. Enfoca un bien cultural con la cámara.');
    }

    /**
     * Bucle principal: predice sobre el frame actual del video
     * y decide si debe consultarse la API de Laravel.
     */
    async function bucleDePrediccion() {
        if (!modelo) return;

        const predicciones = await modelo.predict(videoEl);

        // Se toma la predicción con mayor probabilidad.
        const mejorPrediccion = predicciones.reduce((mejor, actual) =>
            actual.probability > mejor.probability ? actual : mejor
        );

        const etiquetaDetectada = mejorPrediccion.className;
        const confianza = mejorPrediccion.probability;

        if (confianza >= umbralConfianza) {
            if (etiquetaDetectada !== etiquetaActualMostrada && !bloqueadoPorConsulta) {
                await consultarInformacionDelBien(etiquetaDetectada);
            }
        } else if (!bloqueadoPorConsulta) {
            mostrarEstado('Buscando bien cultural... enfoca con más luz y estabilidad.');
        }

        animacionEnCurso = requestAnimationFrame(bucleDePrediccion);
    }

    /**
     * Consulta la API de Laravel (BienCulturalController::buscarPorLabel)
     * con la etiqueta detectada por el modelo, y pinta el resultado
     * en pantalla sin recargar la página.
     */
    async function consultarInformacionDelBien(labelDetectado) {
        bloqueadoPorConsulta = true;
        mostrarEstado('Elemento detectado. Consultando información histórica...');

        try {
            const respuesta = await fetch(urlApiBusqueda, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ label: labelDetectado }),
            });

            if (respuesta.status === 404) {
                mostrarEstado('Elemento no registrado aún en la base de datos.');
                bloqueadoPorConsulta = false;
                return;
            }

            if (!respuesta.ok) {
                throw new Error('Respuesta inesperada del servidor: ' + respuesta.status);
            }

            const json = await respuesta.json();

            if (json.encontrado) {
                pintarResultado(json.data);
                etiquetaActualMostrada = labelDetectado;
            }
        } catch (error) {
            mostrarEstado('Ocurrió un error al consultar la información. Intenta nuevamente.', true);
            console.error('Error al consultar la API de bienes culturales:', error);
        } finally {
            // Pequeña pausa antes de permitir una nueva consulta,
            // para evitar llamadas repetidas mientras el usuario
            // sigue enfocando el mismo objeto.
            setTimeout(() => {
                bloqueadoPorConsulta = false;
            }, 3000);
        }
    }

    /**
     * Pinta dinámicamente la ficha histórica del bien cultural
     * en el panel de resultados.
     */
    function pintarResultado(data) {
        estadoDeteccion.style.display = 'none';
        panelResultado.style.display = 'block';

        resultadoNombre.textContent = data.nombre;

        const autorSiglo = [data.autor, data.siglo].filter(Boolean).join(' — ');
        resultadoAutorSiglo.textContent = autorSiglo || 'Autoría y siglo no registrados';

        resultadoResena.textContent = data.resena_historica;

        if (data.iconografia) {
            resultadoIconografia.textContent = data.iconografia;
            resultadoIconografiaWrapper.style.display = 'block';
        } else {
            resultadoIconografiaWrapper.style.display = 'none';
        }
    }

    /**
     * Actualiza el texto de estado visible mientras no hay un
     * resultado confirmado en pantalla.
     */
    function mostrarEstado(mensaje, esError = false) {
        panelResultado.style.display = 'none';
        estadoDeteccion.style.display = 'block';
        estadoDeteccion.innerHTML = esError
            ? `<span class="text-danger">${mensaje}</span>`
            : `<span class="spinner-border spinner-border-sm"></span> ${mensaje}`;
    }

    /**
     * Punto de entrada: inicializa cámara y modelo, y arranca el bucle.
     */
    async function iniciar() {
        try {
            await iniciarCamara();
            await cargarModeloIA();
            bucleDePrediccion();
        } catch (error) {
            console.error('No fue posible iniciar el módulo de escaneo:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', iniciar);

    // Libera la cámara y detiene el bucle si el usuario sale de la vista.
    window.addEventListener('beforeunload', () => {
        if (animacionEnCurso) cancelAnimationFrame(animacionEnCurso);
        if (videoEl.srcObject) {
            videoEl.srcObject.getTracks().forEach((track) => track.stop());
        }
    });
})();
