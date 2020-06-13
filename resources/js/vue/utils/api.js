import _ from 'lodash'

export const extractErrors = ({e, defaultMsg}) => {
    if (401 === _.get(e, 'response.status')) {
        return [`${defaultMsg} : not authorised - do you have permission?`]
    } else if (429 === _.get(e, 'response.status')) {
        return [`${defaultMsg} : too many attempts - try again in around one minute` ]
    } else if (_.has(e, 'response.data.errors')) {
        const errors = _.get(e, 'response.data.errors')
        return Object.values(errors).reduce((a, b) => a.concat(b), []);
    } else {
        return [`${defaultMsg} : unknown - contact support with page URL and screenshot`]
    }
}