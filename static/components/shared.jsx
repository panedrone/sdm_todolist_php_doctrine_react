// import * as ReactDOM from "react-dom";
//
// import fire from "./event_bus";

const shared = {
    /*  export */ render: function (component, containerID) {
        ReactDOM.render(component, document.getElementById(containerID))
    }
}

fire.setVisibleProjectDetails = (yes) => {
    let el = document.getElementById('projectDetails')
    if (yes) {
        el.style.display = "table-cell"; // to show
    } else {
        el.style.display = "none"; // to hide
        fire.setVisibleTaskForm(false)
    }
}

fire.setVisibleTaskForm = (yes) => {
    let el = document.getElementById('taskForm')
    if (yes) {
        el.style.display = "table-cell"; // to show
        // el.style.display = "block"; // to show
    } else {
        el.style.display = "none"; // to hide
    }
}
