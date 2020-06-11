export const parseDate = (dateString) => {
    return moment(dateString)
}

export const formatDate = (date, format="MMM D, YYYY") => {
    return date.format(format)
}

export const dateRange = (type) => {
    if ('today' == type) {
        const startDate = moment()
        const endDate = startDate
        return {startDate, endDate}
    } else if ('yesterday' == type) {
        const startDate = moment().subtract(1, 'day')
        const endDate = startDate
        return {startDate, endDate}
    } else if ('past-1-week' == type) {
        const startDate = moment().subtract(1, 'week')
        const endDate = moment()
        return {startDate, endDate}
    } else if ('past-3-month' == type) {
        const startDate = moment().subtract(3, 'month')
        const endDate = moment()
        return {startDate, endDate}
    } else {
        const startDate = moment().subtract(1, 'month')
        const endDate = moment()
        return {startDate, endDate}       
    }
}