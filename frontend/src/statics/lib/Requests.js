import axios from 'axios'


const RequestService = {
    post(postObj) {
        /**
         * Perform an axios POST request
         */
        return new Promise((resolve, reject) => {
            axios.post(postObj.url,postObj.params)
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
    getBlob(getObj) {
        /**
         * Perform an axios GET request
         */
        return new Promise((resolve, reject) => {

            axios.get(getObj.url,{
                params: getObj.params
            }, getObj.responseType)
            .then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                let filename = getObj["filename_prefix"]+new Date().getTime()+".csv";
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
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
