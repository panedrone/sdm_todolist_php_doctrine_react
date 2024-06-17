// import * as React from "react";
//
// import * as shared from "./shared";
// import {StringField} from "./form_components";
// import fire from './event_bus'
// import * as api from "./api"

let _updateCurrentProjectName = (_) => {
}

const _fieldCurrentProjectName = <StringField onChange={v => {
    _currentProject.p_name = v
}} saveUpdater={(updater) => {
    _updateCurrentProjectName = updater
}}/>

const ProjectButtons = () => {

    function _projectUpdate() {
        let p_id = _currentProject.p_id
        let json = JSON.stringify(vars.currentProject)
        api.putJson200("api/projects/" + p_id, json, () => {
            fire.fetchProjects();
        })
    }

    function _projectDelete() {
        let p_id = _currentProject.p_id
        api.delete204(`api/projects/${p_id}`, () => {
            fire.setVisibleProjectDetails(false)
            fire.fetchProjects();
        })
    }

    return (
        <table className="controls">
            <tbody>
            <tr>
                <td id="currentProjectName">
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&#x2713;" onClick={() => _projectUpdate()}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="x" onClick={() => _projectDelete()}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&lt;" onClick={() => fire.setVisibleProjectDetails(false)}/>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    )
}

let _updateProjectTasks = (_) => {
}

const ProjectTasks = () => {

    const [data, setData] = React.useState([])

    _updateProjectTasks = setData

    return data.map((task, index) => {
            return (
                <tr key={index}>
                    <td className="w1 nowrap">
                        {task.t_date}
                    </td>
                    <td onClick={() => fire.fetchTask(task.t_id)}>
                        <a>{task.t_subject}</a>
                    </td>
                    <td className="center">
                        {task.t_priority}
                    </td>
                </tr>
            )
        }
    )
}

const TaskCreateButton = () => {

    function _taskCreate() {
        let p_id = _currentProject.p_id
        let json = JSON.stringify({"t_subject": _newTaskSubject})
        api.postJson201(`api/projects/${p_id}/tasks`, json, () => {
            fire.fetchProjects();
            fire.fetchProjectTasks(p_id); // update tasks count
        })
    }

    return (
        <a href="#">
            <input type="button" value="+" onClick={() => _taskCreate()}/>
        </a>
    )
}

let _currentProject = {p_id: -1, p_name: ""};

fire.fetchCurrentProject = (p_id) => {
    api.getJson(`api/projects/${p_id}`, (json) => {
        if (!json) {
            fire.showServerError("failed to get project data")
            return
        }
        _currentProject = json
        _updateCurrentProjectName(_currentProject.p_name)
    })
}

fire.fetchProjectTasks = (p_id) => {
    api.getJsonArray(`api/projects/${p_id}/tasks`, (arr) => {
        fire.setVisibleProjectDetails(true)
        if (arr) {
            _updateProjectTasks(arr)
        }
    })
}

const _projectTasks = <ProjectTasks/>

let _newTaskSubject = ""

const _fieldNewTaskSubject = <StringField onChange={v =>
    _newTaskSubject = v
}/>

const project_details = {
    /*  export */ // function
    renderComponents() {
        shared.render(<ProjectButtons/>, 'projectActions') // !!!! ==== before _fieldCurrentProjectName
        shared.render(_fieldCurrentProjectName, 'currentProjectName')
        shared.render(_projectTasks, 'tasks')
        shared.render(_fieldNewTaskSubject, 'newTaskSubject')
        shared.render(<TaskCreateButton/>, 'taskCreate')
    },
}