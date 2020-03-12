import axios from 'axios'


const RequestService = {
    post(postObj) {
        /**
         * Perform an axios POST request
         */
        return new Promise((resolve, reject) => {
            var requestObj = {}

            _.each(postObj, function(value, key) {
                requestObj[key] = value
            })

            requestObj["dataAction"] = postObj.dataAction

            axios.post(postObj.url,requestObj)
            .then((response) => {
                resolve(response)
            })
            .catch((error) => {
                new Error("Request failed: "+error)
                reject(error)        
            })
        })
    },
    get(getObj) {
        /**
         * Perform an axios GET request
         */
        return new Promise((resolve, reject) => {

            axios.get(getObj.url,{
                params: getObj.params
            })
            .then((response) => {
                resolve(response)
            })
            .catch((error) => {
                new Error("Request failed: "+error)
                reject(error)        
            })
        })

    },
    put() {
        /**
         * Perform an axios PUT request
         */
        return new Promise((resolve, reject) => {

        })
    },
    delete(deleteObj) {
        /**
         * Perform an axios DELETE request
         */
        return new Promise((resolve, reject) => {
            axios.delete(deleteObj.url,deleteObj.params)
            .then((response) => {
                resolve(response)
            })
            .catch((error) => {
                new Error("Request failed: "+error)
                reject(error)        
            })
        })
    },
    patch(patchObj) {
        /**
         * Perform an axios PATCH request
         */
        return new Promise((resolve, reject) => {
            axios.patch(patchObj.url,patchObj.params)
            .then((response) => {
                resolve(response)
            })
            .catch((error) => {
                new Error("Request failed: "+error)
                reject(error)        
            })
        })
    }
}

export default RequestService

export { RequestService }
