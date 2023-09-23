<template>
    <div>
        <h1>Dispositivos</h1>
        <svg xmlns="http://www.w3.org/2000/svg" width="150" height="200" viewBox="0 0 150 200">
            <!-- Dibuja el cuerpo del sensor -->
            <rect x="30" y="30" width="90" height="140" rx="20" ry="20" fill="#EFEFEF" />

            <!-- Dibuja el indicador de temperatura -->
            <rect x="40" y="30" width="70" height="140" rx="15" ry="15" fill="#FFFFFF" />
            <rect x="40" y="60" width="70" height="50" rx="15" ry="15" fill="#F44336" />

            <!-- Dibuja el termómetro -->
            <rect x="70" y="175" width="10" height="25" rx="5" ry="5" fill="#666666" />
            <rect x="60" y="200" width="30" height="10" rx="5" ry="5" fill="#666666" />

            <!-- Dibuja el indicador de temperatura -->
            <circle cx="85" cy="85" r="40" fill="none" stroke="#F44336" stroke-width="5" />

            <!-- Etiqueta de temperatura -->
            <text x="75" y="180" text-anchor="middle" font-size="12" fill="#666666">25°C</text>
        </svg>
    </div>
</template>