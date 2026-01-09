export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        panel: '#15171c',
        panelLight: '#1c1f26',
        accent: '#22c55e',
        danger: '#ef4444',
        warning: '#f59e0b',
        info: '#38bdf8',
        muted: '#9ca3af',
        line: '#2a2e37',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
    },
  },
  plugins: [],
};
