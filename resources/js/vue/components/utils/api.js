import _ from 'lodash'

export const extractErrors = ({e, defaultMsg}) => {
    if (401 === _.get(e, 'response.status')) {
        return [`${defaultMsg} : could not authorise the action`]
    } else if (_.has(e, 'response.data.errors')) {
        const errors = _.get(e, 'response.data.errors')
        return Object.values(errors).reduce((a, b) => a.concat(b), []);
    } else {
        return [`${defaultMsg} : unknown - contact support`]
    }
}