export const formatDate = (dateString, format="MMM D, YYYY") => {
    return moment(dateString).format(format)
}
