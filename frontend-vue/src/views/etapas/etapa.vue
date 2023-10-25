<template>
  <div class="h-[750px] w-full ">
    <Breadcrumb parentTitle='Procesos' subParentTitle="Etapa" />
    <div class="h-full w-full flex flex-row justify-between ">
      <div class="h-full w-1/5 p-4 space-y-4">
        <p class="font-bold text-xl mb-4">
          Dispositivos
        </p>
        <div class="flex flex-col items-center w-full ">
          <spinner :show="showSpinnerComponentes"></spinner>
        </div>
        <template v-if="listaComponentes.length > 0">
          <CardDevice v-for="item in listaComponentes" :key="item.id" :nombre="item.Nombre" :ipAddress="item.DireccionIp"
            :value="25" :image="item.tipoComponenteImage" id="agregarNodoButton" @click="cardClicked(item)">
          </CardDevice>
        </template>
        <template v-else>
          <p class="w-full text-center mt-4">No hay dispositivos disponibles.</p>
        </template>

      </div>
      <div class="h-full flex items-center w-10">
        <ArrowSpinner :show="true" />
      </div>
      <Card class="h-full w-3/4 ">
        <!-- <spinner :show="true"></spinner> -->
        <div ref="myDiagramDiv" class="w-full h-full"></div>
      </Card>
    </div>
  </div>
</template>
<script setup>
import Breadcrumb from '@/components/Breadcrumbs.vue';
import { onMounted, ref, onBeforeMount } from 'vue';
import { useRoute } from 'vue-router';
import go from 'gojs';
import Card from '@/components/Card/Card.vue';
import ComponenteController from '../../services/ComponenteController';
import NodoController from '../../services/NodoController';
import LinkController from '../../services/LinkController';
import { appStore } from "@/store/app.js";
import CardDevice from '../../components/Cards/CardDevice.vue'
import spinner from '../components/spinner/spinner.vue';
import ArrowSpinner from '../components/spinner/ArrowSpinner.vue';

const $appstore = appStore();
const procesoId = ref(null);
const etapaId = ref(null);
const route = useRoute();
const myDiagramDiv = ref(null);
const listaComponentes = ref([]);
const listaNodos = ref([]);
let diagram = null;
const nodeDataArray = [];
const linkDataArray = [];
const showSpinnerComponentes = ref(false);

const listaComponentesPromise = ComponenteController.listDispositivosSinNodo();


//$appstore.setGlobalLoading(true)

listaComponentesPromise
  .then((response) => {
    listaComponentes.value = response
    console.log(response)
    $appstore.setGlobalLoading(false)
  })
  .catch((error) => {
    console.error('Error al obtener la lista de componentes:', error);
    $appstore.setGlobalLoading(false)
  });

const cargarListaComponentes = () => {
  showSpinnerComponentes.value = true;
  ComponenteController.listDispositivosSinNodo()
    .then((response) => {
      listaComponentes.value = response
      showSpinnerComponentes.value = false;
    })
    .catch((error) => {
      console.error('Error al obtener la lista de dispositivos:', error);
      showSpinnerComponentes.value = false;
    });
};

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

const createNode = async (id, name, image, pos) => {
  Node(id, name, image, pos)
  const data = {
    Posicion: pos,
    componente_id: id,
    etapa_id: etapaId.value
  }
  try {
    await NodoController.create(data).then(() => {
      createDiagram();
    })

    const index = listaComponentes.value.findIndex(componente => componente.id === id);
    if (index !== -1) {
      listaComponentes.value.splice(index, 1);
    }
  } catch (e) {
    console.log(e);
  }
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

const cardClicked = (dataComponente) => {
  createNode(dataComponente.id, dataComponente.Nombre, dataComponente.tipoComponenteImage, "-200 -200")
}

const createDiagram = async () => {

  nodeDataArray.splice(0, nodeDataArray.length);
  const listaNodosPromise = await NodoController.list(etapaId.value);
  const response = await listaNodosPromise;

  const listaLinksPromise = await LinkController.list(etapaId.value);
  const responseLinks = await listaLinksPromise;
  console.log(responseLinks)
  for (const nodo of response) {
    const componente = await ComponenteController.getById(nodo.componente_id)
    Node(nodo.id, componente.Nombre, componente.tipoComponenteImage, nodo.Posicion);
  }

  for (const link of responseLinks) {
    console.log(link)
    Link(link.id, link.nodo_from_id, link.nodo_to_id);
  }

  listaNodos.value = response;


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


  diagram.addDiagramListener('SelectionDeleted', async (event) => {
    const deletedNodes = [];
    const deletedLinks = [];

    event.subject.each(function (part) {
      if (part instanceof go.Node) {
        deletedNodes.push(part.data);
      } else if (part instanceof go.Link) {
        deletedLinks.push(part.data);
      }
    });

    for (const nodeData of deletedNodes) {
      const nodo = listaNodos.value.find((nodo) => nodo.id === nodeData.key);
      if (nodo) {
        const id = nodo.componente_id;
        try {
          await NodoController.deleteByComponent(id);
          cargarListaComponentes();
        } catch (error) {
          console.error('Error al eliminar el nodo:', error);
        }
      }
    }

    for (const linkData of deletedLinks) {
      const linkId = linkData.__gohashid;
      console.log(linkData)
      try {
        await LinkController.delete(linkId);
      } catch (error) {
        console.error('Error al eliminar el enlace:', error);
      }
    }
  });

  diagram.addDiagramListener('InitialLayoutCompleted', () => {
    diagram.addDiagramListener('LinkDrawn', (event) => {
      if (event.subject instanceof go.Link) {
        const newLink = event.subject;
        const data = {
          nodo_from_id: newLink.data.from,
          nodo_to_id: newLink.data.to,
          etapa_id: etapaId.value,
          proceso_id: procesoId.value
        };

        LinkController.create(data)
          .then((createdLink) => {
            newLink.data.__gohashid = createdLink.id;

          })
          .catch((error) => {
            // Maneja cualquier error que ocurra al crear el enlace en la base de datos
            console.error('Error al crear el enlace:', error);
            // Si hay un error, podrías considerar eliminar el enlace del diagrama
            diagram.remove(newLink);
          });
      } else {
        console.error('Event.subject no es un enlace válido.');
      }
    });
  });

  diagram.addDiagramListener("SelectionMoved", async function (e) {
    e.subject.each(async function (part) {
      console.log(part.data.pos)
      const { key, pos } = part.data
      console.log(pos)
      const response = await NodoController.updatePosition(key, pos);
      console.log(response)
    });
  });


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

onBeforeMount(()=>{
  procesoId.value = route.params.procesoId;
  etapaId.value = route.params.etapaId;
    window.Echo.channel('update-nodo-position.' + etapaId.value + '.' + 'procesoId.value').listen('Hello', (e)=>{
        console.log(e);
    })
})

onMounted(() => {
  $appstore.setGlobalLoading(true)
  createDiagram();
  console.log(linkDataArray)
});
</script>