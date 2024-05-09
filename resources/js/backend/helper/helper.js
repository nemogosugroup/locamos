const excerpt = (desc, number) => {
    if (desc && desc.length > 0) {
        desc = desc.split(' ');
        if (desc.length > number) {
            desc = desc.slice(0, number).join(' ') + "...";
        }else{
            desc = desc.join(' ')
        }
    }
    return desc;
}
export default {
    excerpt
};