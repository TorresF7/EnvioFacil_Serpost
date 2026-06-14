export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{vue,js}',
    ],
    theme: {
        extend: {
            colors: {

                serpost: {
                    DEFAULT: '#1b3c8c',
                    dark: '#0f2a66',
                    azul: '#14306f',
                    verde: '#1f9d57',
                    naranja: '#e0a106',
                },
                rojo: { DEFAULT: '#d11f2d', dark: '#a8121f' },

                go: '#1f9d57',
                ambar: '#e0a106',
                stop: '#d11f2d',

                fondo: '#f0eee8',
                surface: '#ffffff',
                surface2: '#f7f5ef',
                borde: '#e1ded4',
                line: '#e1ded4',
                texto: {
                    fuerte: '#191b21',
                    medio: '#4e525b',
                    suave: '#878b93',
                },

                'serpost-tint': '#eef1f8',
                'serpost-line': '#c9d2e6',
                'paper-line': '#d8d4c7',
                'go-tint': '#e6f4ec',
                'ambar-tint': '#fbf2db',
                'stop-tint': '#fbe7e8',
                ink: '#191b21',
            },

            fontSize: {
                caption: ['clamp(0.78rem, 0.74rem + 0.2vw, 0.86rem)', { lineHeight: '1.35' }],
                body: ['clamp(0.95rem, 0.92rem + 0.25vw, 1.05rem)', { lineHeight: '1.55' }],
                'body-lg': ['clamp(1.05rem, 1rem + 0.4vw, 1.2rem)', { lineHeight: '1.5' }],
                title: ['clamp(1.2rem, 1.1rem + 0.6vw, 1.5rem)', { lineHeight: '1.25', letterSpacing: '-0.01em', fontWeight: '800' }],
                display: ['clamp(1.6rem, 1.35rem + 1.4vw, 2.25rem)', { lineHeight: '1.12', letterSpacing: '-0.02em', fontWeight: '900' }],
                code: ['clamp(1rem, 0.95rem + 0.4vw, 1.25rem)', { lineHeight: '1.2', letterSpacing: '0.04em' }],
            },
            spacing: {

                section: '1.5rem',
                block: '2rem',
            },
            borderRadius: {

                xs: '6px',
                input: '10px',
                card: '14px',
                panel: '20px',
            },
            boxShadow: {
                card: '0 1px 3px rgba(15,42,102,0.08), 0 1px 2px rgba(15,42,102,0.04)',
            },
            fontFamily: {

                display: ['Archivo', 'system-ui', 'Segoe UI', 'sans-serif'],
                sans: ['Public Sans', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
                mono: ['IBM Plex Mono', 'ui-monospace', 'SFMono-Regular', 'monospace'],
            },
            maxWidth: {
                col: '680px',
                'col-wide': '1040px',
            },
        },
    },
    plugins: [],
};
