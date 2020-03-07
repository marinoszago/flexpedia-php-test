import { axios } from 'axios'

const RequestService = {
    get(data) {

        console.log("DATA", data)
        return new Promise((resolve, reject) => {
            axios.get(data.url, data.params)
            .then(function (response) {
                // handle success
                console.log(response);
                resolve(response)
            })
            .catch(function (error) {
                // handle error
                console.log(error);
                reject(error)
            })
        })
    },
    post() {
        console.log("DATA", data)
        return new Promise((resolve, reject) => {
            axios.post(data.url, data.params)
            .then(function (response) {
                // handle success
                console.log(response);
                resolve(response)
            })
            .catch(function (error) {
                // handle error
                console.log(error);
                reject(error)
            })
        })
    },
    delete() {
        console.log("DATA", data)
        return new Promise((resolve, reject) => {
            axios.delete(data.url, data.params)
            .then(function (response) {
                // handle success
                console.log(response);
                resolve(response)
            })
            .catch(function (error) {
                // handle error
                console.log(error);
                reject(error)
            })
        })
    }
}

export default RequestService