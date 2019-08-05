'use strict';

window.onload = () => {
    const data = JSON.parse(document.getElementById('swagger-data').innerText);
    const ui = SwaggerUIBundle({
        spec: data.spec,
        dom_id: '#swagger-ui',
        validatorUrl: null,
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset,
        ],
        plugins: [
            SwaggerUIBundle.plugins.DownloadUrl,
        ],
        layout: 'StandaloneLayout',
    });

    window.ui = ui
};
