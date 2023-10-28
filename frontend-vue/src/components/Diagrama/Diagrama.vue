<template>
  <div ref="myDiagramDiv" class="w-full h-full"></div>
</template>

<script setup>
import { defineProps, onMounted, ref } from 'vue';
import NodoController from '@/services/NodoController';
import LinkController from '@/services/LinkController';
import ComponenteController from '@/services/ComponenteController';
import go from 'gojs';

const props = defineProps({
  procesoId: { type: Number, required: true },
  etapaId: { type: Number, required: true },
  readOnly: { type: Boolean, required: true }
})

let diagram = null;
const nodeDataArray = [];
const linkDataArray = [];
const listaNodos = ref([]);
const myDiagramDiv = ref(null);
const showSpinnerComponentes = ref(false);
const listaComponentes = ref([]);



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

const cargarListaComponentes = () => {
  showSpinnerComponentes.value = true;
  ComponenteController.listDispositivosSinNodo()
    .then((response) => {
      listaComponentes.value = response
      showSpinnerComponentes.value = false;
    })
    .catch((error) => {
      showSpinnerComponentes.value = false;
    });
};

const createDiagram = async () => {

  nodeDataArray.splice(0, nodeDataArray.length);
  const [response,responseLinks] = await Promise.all([NodoController.list(props.etapaId),LinkController.list(props.etapaId)]);

  await Promise.all(response.map(async(nodo)=>{
    const { componente } = nodo;
    Node(nodo.id, componente.Nombre, componente.tipoComponenteImage, nodo.Posicion);
  }));

  responseLinks.map((link)=>{
    Link(link.id, link.nodo_from_id, link.nodo_to_id);
  });

  listaNodos.value = response;

  const $ = go.GraphObject.make;
  if (diagram) {
    diagram.div = null;
  }
  diagram = $(go.Diagram, myDiagramDiv.value,
    {
      isReadOnly: props.readOnly
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
          etapa_id: props.etapaId,
          proceso_id: props.procesoId
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
      const { key, pos } = part.data
      const response = await NodoController.updatePosition(key, pos);
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
      $(go.Shape, { isPanelMain: true, stroke: "#afd4fe", strokeWidth: 10 },
        new go.Binding("stroke", "fromNode", "#faadc1").ofObject(),
        new go.Binding("stroke", "color", colorFunc)),
      $(go.Shape, { isPanelMain: true, stroke: "white", strokeWidth: 3, name: "PIPE", strokeDashArray: [20, 40] })
    );

    var opacity = 1;
    var down = true;
    function loop() {
    diagram.links.each(link => {
      var shape = link.findObject("PIPE");
      var off = shape.strokeDashOffset - 1;
      // animate (move) the stroke dash
      shape.strokeDashOffset = (off <= 0) ? 60 : off;
      // animate (strobe) the opacity:
      if (down) opacity = opacity - 0.01;
      else opacity = opacity + 0.003;
      if (opacity <= 0) { down = !down; opacity = 0; }
      if (opacity > 1) { down = !down; opacity = 1; }
      shape.opacity = opacity;
    });

    requestAnimationFrame(loop);
  }
  loop();

  diagram.model = new go.GraphLinksModel(nodeDataArray, linkDataArray)
}



onMounted(() => {
  createDiagram();
});
</script>