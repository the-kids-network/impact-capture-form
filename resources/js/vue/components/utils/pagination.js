export const numberOfPages = (items, itemsPerPage) => Math.ceil(items.length / itemsPerPage);

export const itemsForPage = (items, pageNumber, itemsPerPage) => {
    let from = (pageNumber * itemsPerPage) - itemsPerPage;
    let to = (pageNumber * itemsPerPage);
    return items.slice(from, to);
}