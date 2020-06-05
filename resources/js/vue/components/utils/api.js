import _ from 'lodash'

export const extractErrors = ({e, defaultMsg}) => {
    if (_.has(e, 'response.data.errors')) {
        const errors = e.response.data.errors
        return Object.values(errors).reduce((a, b) => a.concat(b), []);
    } else {
        return [defaultMsg]
    }
}