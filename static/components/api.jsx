// import * as React from "react";
//
// import fire from "./event_bus";
// import {render} from "./shared";
// import {ErrorArea} from "./error_area";

const JSON_HEADERS = {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
};

const api = {
    /*  export */
    getJson: function (uri, onSuccess) {
        fetch(uri)
            .then(async (resp) => {
                if (resp.status === 200) {
                    let json = await resp.json()
                    onSuccess(json)
                    return
                }
                await _showStatusError(resp);
            })
            .catch((reason) => {
                _showException(reason)
            })
    },
    /*  export */
    getJsonArray: function (uri, onSuccess) {
        fetch(uri)
            .then(async (resp) => {
                if (resp.status === 200) {
                    let arr = await _responseToArray(resp)
                    onSuccess(arr)
                    return
                }
                await _showStatusError(resp);
            })
            .catch((reason) => {
                _showException(reason)
            })
    },
    /*  export */
    getText: function (uri, onSuccess) {
        fetch(uri)
            .then(async (resp) => {
                if (resp.status === 200) {
                    let text = await resp.text()
                    onSuccess(text)
                    return
                }
                await _showStatusError(resp);
            })
            .catch((reason) => {
                _showException(reason)
            })
    },
    /*  export */
    postJson201: function (uri, json, onSuccess) {
        fetch(uri, {
            method: 'post',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 201) {
                    onSuccess()
                    return
                }
                await _showStatusError(resp)
            })
            .catch((reason) => {
                _showException(reason)
            })
    },
    /*  export */
    putJson200: function (uri, json, onSuccess) {
        fetch(uri, {
            method: 'put',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 200) {
                    onSuccess();
                    return
                }
                await _showStatusError(resp)
            })
            .catch((reason) => {
                _showException(reason)
            })
    },
    /*  export */
    putJson: function (uri, json, onResp) {
        fetch(uri, {
            method: 'put',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                onResp(resp)
            })
            .catch((reason) => {
                _showException(reason)
            })
    },
    /*  export */
    delete204: function (uri, onSuccess) {
        fetch(uri, {
            method: 'delete'
        })
            .then(async (resp) => {
                if (resp.status === 204) {
                    onSuccess();
                    return
                }
                await _showStatusError(resp)
            })
            .catch((reason) => {
                _showException(reason)
            })
    },
    /*  export */
    unicodeToChar: function (text) {
        // https://stackoverflow.com/questions/17267329/converting-unicode-character-to-string-format
        if (!text) {
            return ""
        }
        text = text.toString()
        return text.replace(/\\u[\dA-F]{4}/gi,
            function (match) {
                return String.fromCharCode(parseInt(match.replace(/\\u/g, ''), 16));
            });
    },
    /*  export */
    renderComponents: function () {
        shared.render(serverError, 'serverError');
    },
}

async function _responseToArray(resp) {
    let contentType = resp.headers.get('content-type')
    if (!contentType) {
        console.log("no 'content-type' ==> ")
        console.log(resp)
        let msg = resp.status.toString() + " ==> (no 'content-type') ==>" + resp.toString()
        _showServerError(msg)
        return []
    }
    if (contentType.includes("application/json")) {
        let json = await resp.json()
        if (!json) {
            return []
        }
        if (Array.isArray(json)) {
            // console.log(contentType)
            // console.log(json)
            return json
        }
        let msg = resp.status.toString() + " ==> (not an Array) ==> "
        console.log(msg)
        console.log(json)
        _showServerError(msg + JSON.stringify(json))
        return []
    }
    let text = await resp.text()
    _showServerError(resp.status.toString() + " ==> (" + contentType + ") ==> " + text)
    return []
}

fire.showServerError = (msg) => {
    msg = api.unicodeToChar(msg);
    msg = msg.replace(/\\"/g, '"');
    // console.log(msg)
    _updateServerError(msg)
}

async function _showStatusError(resp) {
    let msg = await resp.text()
    if (!msg) {
        msg = "(no message)"
    }
    fire.showServerError(resp.status.toString() + " ==> " + msg);
}

function _showException(reason) {
    console.log(".catch((reason) => {")
    console.log(reason)
    fire.showServerError(reason.toString())
}

let _updateServerError = (_) => {
}

const serverError = <ErrorArea saveUpdater={(updater) => _updateServerError = updater}/>


