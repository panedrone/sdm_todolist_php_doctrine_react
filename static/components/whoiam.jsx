// import * as React from "react";
//
// import * as shared from './shared.jsx'
// import * as api from "./api"
// import {RawHtml} from "./raw_html"

const whoiam = {
    /*  export */
    fetchWhoIAm: function () {
        api.getText('api/whoiam', (res) => {
            if (!res) {
                res = '== unknown =='
            }
            res += ", in-browser babel transformer, react " + React.version
            shared.render(<RawHtml rawHtml={res}/>, 'whoiam')
        })
    },
}