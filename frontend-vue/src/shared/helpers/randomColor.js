export const randomColor = ()=>{
    const colors = ["#25cede","#6bdee8","#aad8e2","#d1dae1","#d2bcb2","#f1d4c1"]
    const random =   Math.floor(Math.random() * (colors.length - 1 + 1)) + 1;
    const index = random -1;
    return colors[index];
    
}