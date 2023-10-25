<template>
  <div class="flex flex-row w-auto ">
    <div class="flex flex-col w-3/4 mr-2">

      <Card class=" h-[750px] w-full">
        <select id="small"
          class="w-80 p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none"
          v-model="selectedProcess">
          <option value="" disabled selected>
            Selecciona un proceso
          </option>
          <option v-for="proceso in listaProcesos" :key="proceso.id" :value="proceso.id">
            {{ proceso.Nombre }}
          </option>
        </select>
        <div v-for="(etapaNodos, index) in listaNodos.value" :key="index">
          <p>{{ etapaNodos[0].etapaNombre }}</p>
          <div :ref="'myDiagramDiv'" class="my-diagram">
            <!-- Aquí se creará el diagrama para esta etapa -->
          </div>
        </div>
      </Card>
      <Card class=" h-[300px] w-full  mt-6">
        <!-- <BaseBtn id="agregarNodoButton">add</BaseBtn>
-->
        <BaseBtn @click="Print">print</BaseBtn>
      </Card>
    </div>
    <Card class="h-[1074px] w-1/4 ml-4">

    </Card>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import go from 'gojs';
import { appStore } from "@/store/app.js";
import ProcesoController from '@/services/ProcesoController.js';
import EtapaController from '@/services/EtapaController.js';
import NodoController from '@/services/NodoController.js';
import LinkController from '@/services/LinkController.js';
import ComponenteController from '@/services/ComponenteController.js';
import Card from '@/components/Card/Card.vue';


const $appstore = appStore();
const userData = $appstore.getUserData
//console.log(userData)
const listaProcesosPromise = ProcesoController.getProcesosByUser(userData.id);

const listaProcesos = ref([]);
const selectedProcess = ref("");
const listaEtapas = ref([]);
const listaNodos = ref([]);
const listaLinks = ref([]);
const dispositivos = ref([]);

const nodeDataArray = [];
const linkDataArray = [];
let diagram = null;

$appstore.setGlobalLoading(true)
listaProcesosPromise
  .then((response) => {
    listaProcesos.value = response.reverse()
    $appstore.setGlobalLoading(false)
  })
  .catch((error) => {
    console.error('Error al obtener la lista de procesos:', error);
    $appstore.setGlobalLoading(false)
  });

watch(selectedProcess, async (newVal) => {
  if (newVal) {
    try {
      const response = await EtapaController.listaEtapas(newVal)
        .then((response) => {
          console.log(response.data)
          listaEtapas.value = response.data;

          response.data.forEach((etapa) => {
            console.log(etapa.id);
            getNodos(etapa.id);
            getLinks(etapa.id);
          });
        })
        .catch((error) => {
          console.error('Error al obtener la lista de procesos:', error);
          $appstore.setGlobalLoading(false)
        });
    } catch (error) {
      console.error("Error al obtener las etapas:", error);
    }
  } else {
    listaEtapas.value = [];
  }
});

const getNodos = async (etapaId) => {
  const nodos = await NodoController.list(etapaId);
  listaNodos.value.push(nodos);
}

const getLinks = async (etapaId) => {
  const links = await LinkController.list(etapaId);
  listaLinks.value.push(links);
}


const getDispositivos = async () => {
  for (const nodo of listaNodos) {
    const dispositivo = await ComponenteController.getById(nodo.dispositivoId);
    dispositivos.value.push(...dispositivo)
  }
}


const Print = () => {
  console.log(listaLinks.value)
  console.log(listaNodos.value)
}

const Node = (id, name, image, pos) => {
  const newNodeData = {
    key: id,
    pos: pos,
    text: name,
    picture: image
  };
  nodeDataArray.push(newNodeData);
}

const Link = (id, from, to) => {
  const newLinkData = {
    __gohashid: id,
    from: from,
    to: to,
  };
  linkDataArray.push(newLinkData);
}

const myDiagramDiv = ref(null);

function print() {
  console.log("nodos:");
  for (const node of nodeDataArray) {
    console.log(node);
  }

  console.log("links:");
  for (const link of linkDataArray) {
    console.log(link);
  }
}



const createDiagram = async (etapaNodos, links) => {

  for (const nodo in listaNodos) {
    Node(nodo.id, "Lallala", "laskdf", nodo.pos)
  }

  const $ = go.GraphObject.make;
  if (diagram) {
    diagram.div = null;
  }
  diagram = $(go.Diagram, myDiagramDiv.value,
    {
      //isReadOnly: true
    });

  var Colors = {
    "red": "#be4b15",
    "green": "#52ce60",
    "blue": "#6ea5f8",
    "lightred": "#fd8852",
    "lightblue": "#afd4fe",
    "lightgreen": "#b9e986",
    "pink": "#faadc1",
    "purple": "#d689ff",
    "orange": "#f08c00"
  }

  var ColorNames = [];
  for (var n in Colors) ColorNames.push(n);

  // a conversion function for translating general color names to specific colors
  function colorFunc(colorname) {
    var c = Colors[colorname]
    if (c) return c;
    return "gray";
  }

  diagram.groupTemplate =
    $(go.Group, "Spot",
      {
        layerName: "Background",
        ungroupable: true,
        locationSpot: go.Spot.Center,
        selectionObjectName: "BODY",
        computesBoundsAfterDrag: true,  // allow dragging out of a Group that uses a Placeholder
        handlesDragDropForMembers: true,  // don't need to define handlers on Nodes and Links
        mouseDrop: (e, grp) => {  // add dropped nodes as members of the group
          var ok = grp.addMembers(grp.diagram.selection, true);
          if (!ok) grp.diagram.currentTool.doCancel();
        },
        avoidable: false
      },
      new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
      $(go.Panel, "Auto",
        { name: "BODY" },
        $(go.Shape,
          {
            parameter1: 10,
            fromLinkable: true, toLinkable: true,
            fromLinkableDuplicates: true, toLinkableDuplicates: true,
            fromSpot: go.Spot.AllSides, toSpot: go.Spot.AllSides
          },
          new go.Binding("fill"),
          new go.Binding("stroke", "color"),
          new go.Binding("strokeWidth", "thickness"),
          new go.Binding("strokeDashArray", "dash")),
        $(go.Placeholder,
          { background: "transparent", margin: 20 })
      ),
      $(go.TextBlock,
        {
          alignment: go.Spot.Top, alignmentFocus: go.Spot.Bottom,
          font: "bold 12pt sans-serif", editable: true
        },
        new go.Binding("text"),
        new go.Binding("stroke", "color"))
    );

  diagram.nodeTemplate =
    $(go.Node, "Auto",
      {
        locationObjectName: "BLOCK",
        locationSpot: go.Spot.Bottom,
      },
      new go.Binding("location", "pos", go.Point.parse).makeTwoWay(go.Point.stringify),

      // Contenedor principal
      $(go.Panel, "Vertical",
        { name: "BLOCK" },
        // Cuadrado con la imagen (para crear enlaces)
        $(go.Picture,
          {
            name: "picture",
            desiredSize: new go.Size(50, 50), // Tamaño deseado de la imagen
            portId: "", fromLinkable: true, toLinkable: true,
          },
          new go.Binding("source", "picture")),
        // TextBlock (para mover el nodo)
        $(go.TextBlock,
          {
            font: "bold 12px sans-serif",
            textAlign: "center",
            cursor: "move",  // Cursor de movimiento
            margin: new go.Margin(10, 0, 0, 0), // Ajusta el margen superior para separar el texto del icono
          },
          new go.Binding("text", "text")
        )
      )
    );

  diagram.linkTemplate =
    $(go.Link,
      {
        layerName: "Background",
        routing: go.Link.Orthogonal,
        corner: 15,
        reshapable: true,
        resegmentable: true,
      },
      new go.Binding("fromSpot", "fromSpot", go.Spot.parse),
      new go.Binding("toSpot", "toSpot", go.Spot.parse),
      new go.Binding("points").makeTwoWay(),
      $(go.Shape, { isPanelMain: true, stroke: "gray", strokeWidth: 10 },
        // get the default stroke color from the fromNode
        new go.Binding("stroke", "fromNode", n => go.Brush.lighten((n && Colors[n.data.color]) || "gray")).ofObject(),
        // but use the link's data.color if it is set
        new go.Binding("stroke", "color", colorFunc)),
      $(go.Shape, { isPanelMain: true, stroke: "white", strokeWidth: 3, name: "PIPE", strokeDashArray: [20, 40] })
    );



  diagram.model = new go.GraphLinksModel(nodeDataArray, linkDataArray)
  $appstore.setGlobalLoading(false)
}

onMounted(() => {
  createDiagram();
});

</script>

<style>
.drop-shadow {
  filter: drop-shadow(3px 3px 5px rgba(0, 0, 0, 0.5));
}
</style>
