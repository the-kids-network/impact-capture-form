import _ from 'lodash'

export const mapErrors = ({e, actionName}) =>{
    const rootMessage = `Failure --> ${actionName}`

    console.log(rootMessage)
    console.log(e)

    const error = {
        rootMessage,
        messages: messagesFromError(e)
    }

    console.log(error)

    return error
}

const messagesFromError = e => {
    if (401 === _.get(e, 'response.status')) {
        return [`Not authorised - do you have permission?`]
    } else if (429 === _.get(e, 'response.status')) {
        return [`Too many attempts - try again in around one minute` ]
    } else if (_.has(e, 'response.data.errors')) {
        const errors = _.get(e, 'response.data.errors')
        return Object.values(errors).reduce((a, b) => a.concat(b), []);
    } else {
        return [`Unknown issue(s) - contact support with page URL and screenshot`]
    }
}