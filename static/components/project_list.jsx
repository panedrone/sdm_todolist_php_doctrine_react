// import * as React from "react";
//
// import * as shared from "./shared";
// import {StringField} from "./form_components";
// import fire from './event_bus.jsx'
// import * as api from "./api"

let _updateProjects = (_) => {
}

const ProjectList = () => {

    function _handleClick(project) {
        fire.fetchCurrentProject(project.p_id)
        fire.fetchProjectTasks(project.p_id)
        fire.setVisibleTaskForm(false)
    }

    const [data, setData] = React.useState([])

    _updateProjects = setData

    return data.map((project, index) => {
            return (
                <tr key={index}>
                    <td onClick={() => _handleClick(project)}>
                        <a>{project.p_name}</a>
                    </td>
                    <td className="center w1">
                        {project.p_tasks_count}
                    </td>
                </tr>
            )
        }
    )
}

const ProjectCreateButton = () => {

    function projectCreate() {
        if (_newProjectName.length === 0) {
            _newProjectName = '?'
        }
        let json = JSON.stringify({"p_name": _newProjectName})
        api.postJson201("api/projects", json, () => {
            fire.fetchProjects();
        })
    }

    return (
        <a href="#">
            <input type="button" value="+" onClick={() => projectCreate()}/>
        </a>
    )
}

let _newProjectName = ""

const _fieldNewProjectName = <StringField onChange={v => _newProjectName = v}/>

const project_list = {
    /*  export */
    renderComponents: function () {
        fire.fetchProjects = this.fetchProjects
        shared.render(<ProjectList/>, 'projects')
        shared.render(_fieldNewProjectName, 'newProjectName')
        shared.render(<ProjectCreateButton/>, 'projectCreate')
    },
    /*  export */
    fetchProjects: function () {
        api.getJsonArray("api/projects", (arr) => {
            if (arr) {
                _updateProjects(arr)
            }
        })
    }
}