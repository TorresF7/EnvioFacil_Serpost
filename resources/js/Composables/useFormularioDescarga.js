export function useFormularioDescarga() {
    function descargar(url) {
        if (!url) return;
        const enlace = document.createElement('a');
        enlace.href = url;
        enlace.setAttribute('download', '');
        document.body.appendChild(enlace);
        enlace.click();
        enlace.remove();
    }

    function imprimir(url) {
        if (!url) return;
        window.open(url, '_blank');
    }

    return { descargar, imprimir };
}
