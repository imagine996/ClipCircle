// Tailwind CSS v3.3.0 CDN
// 由于这是演示环境，提供基础样式支持

// Basic Styles
const basicStyles = `
/* CSS Reset */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

/* Basic Layout */
body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  line-height: 1.6;
  color: #333;
}

/* Color System */
.text-gray-500 { color: #6b7280; }
.text-gray-400 { color: #9ca3af; }
.text-gray-600 { color: #4b5563; }
.text-gray-700 { color: #374151; }
.text-pink-600 { color: #db2777; }
.text-pink-500 { color: #ec4899; }
.text-blue-600 { color: #2563eb; }
.text-blue-500 { color: #3b82f6; }
.text-green-600 { color: #16a34a; }
.text-green-500 { color: #22c55e; }
.text-purple-600 { color: #7c3aed; }
.text-purple-500 { color: #8b5cf6; }
.text-red-500 { color: #ef4444; }
.text-red-700 { color: #b91c1c; }
.text-white { color: white; }

/* Background Colors */
.bg-gray-50 { background-color: #f9fafb; }
.bg-white { background-color: white; }
.bg-gray-100 { background-color: #f3f4f6; }
.bg-pink-600 { background-color: #db2777; }
.bg-pink-100 { background-color: #fce7f3; }
.bg-blue-100 { background-color: #dbeafe; }
.bg-green-100 { background-color: #dcfce7; }
.bg-purple-100 { background-color: #f3e8ff; }
.bg-gray-800 { background-color: #1f2937; }
.bg-gray-700 { background-color: #374151; }
.bg-gray-500 { background-color: #6b7280; }

/* Layout Classes */
.flex { display: flex; }
.flex-col { flex-direction: column; }
.justify-between { justify-content: space-between; }
.justify-center { justify-content: center; }
.items-center { align-items: center; }
.items-start { align-items: flex-start; }
.flex-1 { flex: 1; }
.gap-4 { gap: 1rem; }
.gap-6 { gap: 1.5rem; }

/* Grid Layout */
.grid { display: grid; }
.grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }

/* Responsive Layout */
@media (min-width: 768px) {
  .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (min-width: 1024px) {
  .lg\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .lg\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
}

/* Margin Classes */
.mb-4 { margin-bottom: 1rem; }
.mb-6 { margin-bottom: 1.5rem; }
.mt-1 { margin-top: 0.25rem; }
.mt-4 { margin-top: 1rem; }
.ml-20 { margin-left: 5rem; }
.ml-64 { margin-left: 16rem; }
.mx-2 { margin-left: 0.5rem; margin-right: 0.5rem; }
.ml-3 { margin-left: 0.75rem; }
.mr-2 { margin-right: 0.5rem; }
.mr-4 { margin-right: 1rem; }

/* Padding Classes */
.p-4 { padding: 1rem; }
.px-4 { padding-left: 1rem; padding-right: 1rem; }
.py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
.py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
.py-4 { padding-top: 1rem; padding-bottom: 1rem; }
.pl-4 { padding-left: 1rem; }

/* Border Classes */
.border { border: 1px solid; }
.border-gray-200 { border-color: #e5e7eb; }
.border-gray-100 { border-color: #f3f4f6; }
.border-b { border-bottom: 1px solid; }
.rounded { border-radius: 0.25rem; }
.rounded-lg { border-radius: 0.5rem; }
.rounded-full { border-radius: 9999px; }

/* Shadow Classes */
.shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
.shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }

/* Text Classes */
.text-xs { font-size: 0.75rem; line-height: 1rem; }
.text-sm { font-size: 0.875rem; line-height: 1.25rem; }
.text-base { font-size: 1rem; line-height: 1.5rem; }
.text-lg { font-size: 1.125rem; line-height: 1.75rem; }
.text-xl { font-size: 1.25rem; line-height: 1.75rem; }
.text-2xl { font-size: 1.5rem; line-height: 2rem; }
.font-bold { font-weight: 700; }
.font-medium { font-weight: 500; }
.uppercase { text-transform: uppercase; }
.whitespace-nowrap { white-space: nowrap; }
.text-left { text-align: left; }
.text-right { text-align: right; }
.text-gray-500 { color: #6b7280; }
.hover\:underline:hover { text-decoration: underline; }

/* Transition Effects */
.transition { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter, -webkit-backdrop-filter; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
.transition-all { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 300ms; }
.duration-300 { transition-duration: 300ms; }

/* Position Classes */
.fixed { position: fixed; }
.absolute { position: absolute; }
.relative { position: relative; }
.top-0 { top: 0; }
.left-0 { left: 0; }
.right-0 { right: 0; }
.z-40 { z-index: 40; }
.z-30 { z-index: 30; }

/* Width and Height */
.w-6 { width: 1.5rem; }
.w-10 { width: 2.5rem; }
.w-20 { width: 5rem; }
.w-64 { width: 16rem; }
.h-10 { height: 2.5rem; }
.h-16 { height: 4rem; }
.h-64 { height: 16rem; }
.h-screen { height: 100vh; }
.w-full { width: 100%; }

/* Overflow Control */
.overflow-y-auto { overflow-y: auto; }
.overflow-x-auto { overflow-x: auto; }

/* Table Styles */
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  text-align: left;
  padding: 0.5rem 1rem;
}

/* Common Utility Classes */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

/* Form Styles */
input, textarea, select {
  display: block;
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

/* Button Styles */
button {
  padding: 0.5rem 1rem;
  background-color: #4a90e2;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #357abd;
}
`;

// Create style element and append to head
const styleElement = document.createElement('style');
styleElement.textContent = basicStyles;
document.head.appendChild(styleElement);