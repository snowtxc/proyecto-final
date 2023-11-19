<script setup>
    import QuickChart from "quickchart-js";
    import CardReporteProcesoMasActivo from "@/components/Cards/CardReporteProcesoMasActivo.vue";
    import CardAlarmasPorDispositivo from "@/components/Cards/CardAlarmasPorDispositivo.vue";
    import CardDispositivosMasActivosVue from "@/components/Cards/CardDispositivosMasActivos.vue";
    import CardAlarmasPorProceso from "@/components/Cards/CardAlarmasPorProceso.vue";
    import CardDispositivoActivosEInactivos from "@/components/Cards/CardDispositivoActivosEInactivos.vue";
    import CardDispositivosActivosPorProceso from "@/components/Cards/CardDispositivosActivosPorProceso.vue";
    import CardDispositivosPorTipo from "@/components/Cards/CardDispositivosPorTipo.vue";
    import CardAlarmasPorTipoDispositivo from "@/components/Cards/CardAlarmasPorTipoDispositivo.vue";

    import Breadcrumbs from "../../components/Breadcrumbs.vue";

 const  descargarImagenDesdeURL = (url, nombreArchivo)=>{
        fetch(url)
            .then(response => response.blob())
            .then(blob => {
                const urlObjeto = window.URL.createObjectURL(blob);

                const enlace = document.createElement('a');
                enlace.href = urlObjeto;
                enlace.download = nombreArchivo || 'imagen'; 
                document.body.appendChild(enlace);
                enlace.click();
                window.URL.revokeObjectURL(urlObjeto);
                document.body.removeChild(enlace);
    }).catch(error => console.error('Error al descargar la imagen:', error));
}

const generarReport = ({title,labels,values})=>{
    const myChart = new QuickChart();
    myChart.setConfig({
        type: 'bar',
        data: {
            labels,
            datasets: [
            {
                label: title,
                data: values
            },
            ],
        },
        options: {
            scales: {
            yAxes: [
                {
                ticks: {
                    callback: function (value) {
                    return   value;
                    },
                },
                },
            ],
            },
        },
        });

        descargarImagenDesdeURL(myChart.getUrl(), "archivo.png")

}
</script>

<template>
    <Breadcrumbs parentTitle="Reportes del Sistema"></Breadcrumbs>

    <div class="w-3/4 m-auto grid grid-cols-2 gap-4">
      <CardReporteProcesoMasActivo @onReport="generarReport"></CardReporteProcesoMasActivo>
      <CardAlarmasPorDispositivo @onReport="generarReport"></CardAlarmasPorDispositivo>
      <CardDispositivosMasActivosVue @onReport="generarReport"></CardDispositivosMasActivosVue>
      <CardAlarmasPorProceso @onReport="generarReport"></CardAlarmasPorProceso>
      <CardDispositivoActivosEInactivos @onReport="generarReport"></CardDispositivoActivosEInactivos>
      <CardDispositivosActivosPorProceso @onReport="generarReport"></CardDispositivosActivosPorProceso>
      <CardDispositivosPorTipo @onReport="generarReport"></CardDispositivosPorTipo>
      <CardAlarmasPorTipoDispositivo @onReport="generarReport"></CardAlarmasPorTipoDispositivo>      
      
    </div>
    
</template>