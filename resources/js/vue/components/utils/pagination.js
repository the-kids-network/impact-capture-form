export const numberOfPages = (totalItems, itemsPerPage) => Math.ceil(totalItems / itemsPerPage);

export const itemsForPage = (items, pageNumber, itemsPerPage) => {
    let from = (pageNumber * itemsPerPage) - itemsPerPage;
    let to = (pageNumber * itemsPerPage);
    return items.slice(from, to);
}