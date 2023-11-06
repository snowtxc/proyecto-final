export const procesoAddedChannel = () => 'added-proceso';
export const userAddedChannel = () => 'added-user';
export const tipoComponenteAddedChannel = () => 'added-tipo-componente';
export const componenteAddedChannel = () => 'added-componente';
export const procesoDeletedChannel = () => 'deleted-proceso';
export const userDeletedChannel = () => 'deleted-user';
export const tipoComponenteDeletedChannel = () => 'deleted-tipo-componente';  
export const componenteDeletedChannel = () => 'deleted-componente';
export const appendRegistrosDeviceChannel = (componenteId) => `componente.${componenteId}.update-registros`;
export const updateMarcaLast24HourChannel = (componenteId) => `componente.${componenteId}.update-marca-last24hours`;


