// 'use strict';

// https://legacy.reactjs.org/docs/add-react-to-a-website.html
// Add React to a Website
// <p>This page demonstrates using React with no build tooling.</p>
// <p>React is loaded as a script tag.</p>

// import * as React from 'react'
// import ReactDOM from 'https://unpkg.com/react-dom@16/umd/react-dom.production.min.js';

const JSON_HEADERS = {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
};

const RawHtml = ({rawHtml}) => {
    const __html = html => ({
        // === panedrone: wrap it just to get rid of ide warnings
        __html: html, // https://stackoverflow.com/questions/29044518/safe-alternative-to-dangerouslysetinnerhtml
    });
    return (
        <span dangerouslySetInnerHTML={__html(rawHtml)}></span>
    )
}

function fetchWhoIAm() {
    fetch("api/whoiam")
        .then(async (resp) => {
            if (resp.status === 200) {
                let res = await resp.text()
                if (!res) {
                    res = '== unknown =='
                }
                render(<RawHtml rawHtml={res}/>, 'who-I-am')
                return
            }
            await showStatusError(resp);
        })
        .catch((reason) => {
            showException(reason)
        })
}

function fetchProjects() {
    fetch("api/projects")
        .then(async (resp) => {
            if (resp.status === 200) {
                let res = await responseToArray(resp)
                render(<ProjectDetails data={res}/>, 'projects')
                return
            }
            await showStatusError(resp);
        })
        .catch((reason) => {
            showException(reason)
        })
}

function fetchProjectTasks(p_id) {
    fetch("api/projects/" + p_id + "/tasks")
        .then(async (resp) => {
            if (resp.status === 200) {
                setVisibleProjectDetails(true)
                let res = await responseToArray(resp)
                render(<ProjectTasks data={res}/>, 'tasks')
                return
            }
            await showStatusError(resp);
        })
        .catch((reason) => {
            showException(reason)
        })
}

const ProjectDetails = ({data}) => {

    function fetchCurrentProject(p_id) {
        fetch("api/projects/" + p_id)
            .then(async (resp) => {
                if (resp.status === 200) {
                    currentProject = await resp.json()
                    if (!currentProject) {
                        showServerError("failed to get project data")
                        return
                    }
                    updateCurrentProjectName(currentProject.p_name)
                    return
                }
                await showStatusError(resp);
            })
            .catch((reason) => {
                showException(reason)
            })
    }

    function handleClick(project) {
        setVisibleTaskForm(false)
        fetchCurrentProject(project.p_id)
        fetchProjectTasks(project.p_id)
    }

    return data.map((project) => {
            return (
                <tr>
                    <td onClick={() => handleClick(project)}>
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

const TaskTitle = ({initial}) => {
    return <span>{initial}</span>
}

function fetchTask(t_id) {
    let t_id_s = t_id.toString()
    fetch("api/tasks/" + t_id_s)
        .then(async (resp) => {
            if (resp.status === 200) {
                setVisibleTaskForm(true)
                currentTask = await resp.json()
                if (!currentTask) {
                    showServerError("failed to get task data")
                    return
                }
                render(<TaskTitle initial={currentTask.t_subject}/>, 'taskTitle')
                updateSubject(currentTask.t_subject)
                updateDate(currentTask.t_date)
                updatePriority(currentTask.t_priority)
                updateComments(currentTask.t_comments)
                return
            }
            await showStatusError(resp);
        })
        .catch((reason) => {
            showException(reason)
        })
}

const ProjectTasks = ({data}) => {

    return data.map((task) => {
            return (
                <tr>
                    <td className="w1">
                        {task.t_date}
                    </td>
                    <td onClick={() => fetchTask(task.t_id)}>
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

function ProjectCreateButton() {

    function projectCreate() {
        if (newProjectName.length === 0) {
            newProjectName = '?'
        }
        let json = JSON.stringify({"p_name": newProjectName})
        fetch("api/projects", {
            method: 'post',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 201) {
                    fetchProjects();
                    return
                }
                await showStatusError(resp)
            })
            .catch((reason) => {
                showException(reason)
            })
    }

    return (
        <a href="#">
            <input type="button" value="+" onClick={() => projectCreate()}/>
        </a>
    )
}

const TaskCreateButton = () => {

    function taskCreate() {
        let p_id = currentProject.p_id
        let json = JSON.stringify({"t_subject": newTaskSubject})
        fetch("api/projects/" + p_id + "/tasks", {
            method: 'post',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 201) {
                    fetchProjects();
                    fetchProjectTasks(p_id); // update tasks count
                    return
                }
                await showStatusError(resp)
            })
            .catch((reason) => {
                showException(reason)
            })
    }

    return (
        <a href="#">
            <input type="button" value="+" onClick={() => taskCreate()}/>
        </a>
    )
}

const ProjectButtons = () => {

    function projectUpdate() {
        let p_id = currentProject.p_id
        let json = JSON.stringify(currentProject)
        fetch("api/projects/" + p_id, {
            method: 'put',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 200) {
                    fetchProjects();
                    return
                }
                await showStatusError(resp)
            })
            .catch((reason) => {
                showException(reason)
            })
    }

    function projectDelete() {
        let p_id = currentProject.p_id
        fetch("api/projects/" + p_id, {
            method: 'delete'
        })
            .then(async (resp) => {
                if (resp.status === 204) {
                    setVisibleProjectDetails(false)
                    fetchProjects();
                    return
                }
                await showStatusError(resp)
            })
            .catch((reason) => {
                showException(reason)
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
                        <input type="button" value="&#x2713;" onClick={() => projectUpdate()}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="x" onClick={() => projectDelete()}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&lt;" onClick={() => setVisibleProjectDetails(false)}/>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    )
}

const TaskButtons = () => {

    function taskUpdate() {
        if (!isNaN(currentTask.t_priority)) {
            currentTask.t_priority = parseInt(currentTask.t_priority);
            if (!currentTask.t_priority) {
                currentTask.t_priority = 1
            }
        }
        let json = JSON.stringify(currentTask)
        let p_id = currentProject.p_id
        let t_id_s = currentTask.t_id.toString()
        fetch("api/tasks/" + t_id_s, {
            method: 'put',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 200) {
                    fetchProjectTasks(p_id);
                    fetchTask(currentTask.t_id);
                    hideTaskError()
                    return
                }
                await showTaskError(resp)
            })
            .catch((reason) => {
                showException(reason)
            })
    }

    function taskDelete() {
        let p_id = currentProject.p_id
        let t_id = currentTask.t_id
        fetch("api/tasks/" + t_id, {
            method: "delete"
        })
            .then(async (resp) => {
                if (resp.status === 204) {
                    fetchProjects(); // update tasks count
                    fetchProjectTasks(p_id);
                    hideTaskError()
                    setVisibleTaskForm(false)
                } else {
                    let text = await resp.text()
                    showServerError(resp.status + " " + text);
                }
            })
            .catch((reason) => {
                showException(reason)
            })
    }

    return (
        <table className="controls">
            <tbody>
            <tr>
                <td className="w100">
                    <div className="title" id="taskTitle">
                    </div>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&#x2713;" onClick={() => taskUpdate()}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="x" onClick={() => taskDelete()}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&lt;" onClick={() => setVisibleTaskForm(false)}/>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    )
}

const ErrorArea = ({saveUpdater}) => {

    const [value, setValue] = React.useState("")

    if (saveUpdater) {
        saveUpdater(setValue)
    }

    return (
        <div>
            {
                value.length > 0
                &&
                <div>
                    <button onClick={() => setValue("")}>&#x2713;</button>
                    &nbsp;
                    <strong>Error:</strong>&nbsp;{value}
                </div>}
        </div>
    );
}

// https://stackoverflow.com/questions/17267329/converting-unicode-character-to-string-format

function unicodeToChar(text) {
    if (!text) {
        return ""
    }
    text = text.toString()
    return text.replace(/\\u[\dA-F]{4}/gi,
        function (match) {
            return String.fromCharCode(parseInt(match.replace(/\\u/g, ''), 16));
        });
}

let updateServerError = null

const serverError = <ErrorArea saveUpdater={(updater) => updateServerError = updater}/>

render(serverError, 'serverError');

function showServerError(msg) {
    msg = unicodeToChar(msg);
    msg = msg.replace(/\\"/g, '"');
    console.log(msg)
    updateServerError(msg)
}

let updateTaskError = null

const taskError = <ErrorArea saveUpdater={(updater) => updateTaskError = updater}/>

render(taskError, 'taskError');

function hideTaskError() {
    updateTaskError("")
}

async function showTaskError(resp) {
    let msg = await resp.text()
    msg = unicodeToChar(msg);
    // https://stackoverflow.com/questions/6640382/how-to-remove-backslash-escaping-from-a-javascript-var
    msg = msg.replace(/\\\\"/g, '"');
    msg = msg.replace(/\\"/g, '"');
    msg = resp.status.toString() + " ==> " + msg
    updateTaskError(msg)
}

async function responseToArray(resp) {
    let contentType = resp.headers.get('content-type')
    if (!contentType) {
        console.log("no 'content-type' ==> ")
        console.log(resp)
        let msg = resp.status.toString() + " ==> (no 'content-type') ==>" + resp.toString()
        showServerError(msg)
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
        console.log(j)
        showServerError(msg + JSON.stringify(json))
        return []
    }
    let text = await resp.text()
    showServerError(resp.status.toString() + " ==> (" + contentType + ") ==> " + text)
    return []
}

async function showStatusError(resp) {
    let msg = await resp.text()
    if (!msg) {
        msg = "(no message)"
    }
    showServerError(resp.status.toString() + " ==> " + msg);
}

function showException(reason) {
    console.log(".catch((reason) => {")
    console.log(reason)
    showServerError(reason.toString())
}

function setVisibleProjectDetails(yes) {
    if (yes) {
        elementById('projectDetails').style.display = "table-cell"; // to show
    } else {
        elementById('projectDetails').style.display = "none"; // to hide
        setVisibleTaskForm(false)
    }
}

function setVisibleTaskForm(yes) {
    hideTaskError()
    if (yes) {
        elementById('taskForm').style.display = "table-cell"; // to show
        // elementById('taskForm').style.display = "block"; // to show
    } else {
        elementById('taskForm').style.display = "none"; // to hide
    }
}

// ================================================================================

function elementById(id) {
    return document.getElementById(id)
}

function render(component, containerID) {
    ReactDOM.render(component, elementById(containerID))
}

// Components =====================================================================

const StringField = ({onChange, saveUpdater}) => {

    const [value, setValue] = React.useState("")

    if (saveUpdater) {
        saveUpdater(setValue)
    }

    function handleChange(event) {
        let target = event.target.value
        if (onChange) {
            onChange(target)
        }
        setValue(target);
    }

    // <p>
    //     <strong>Current value:</strong>
    //     {value || '(empty)'}
    // </p>

    return (
        <label>
            <input value={value} onChange={handleChange}/>
        </label>
    )
}

const IntegerField = ({onChange, saveUpdater}) => {

    const [value, setValue] = React.useState("")

    if (saveUpdater) {
        saveUpdater(setValue)
    }

    function isInteger(target) {

        // === panedrone: "target" is always a string

        if (!target) {
            return true // === panedrone: allow typing from scratch
        }

        let parsed = parseInt(target)
        let equal = parsed.toString() === target

        return parsed && parsed <= 10 && equal
    }

    function handleChange(event) {
        let target = event.target.value
        let valid = isInteger(target)
        if (!valid) {
            return
        }
        if (onChange) {
            onChange(target)
        }
        setValue(target);
    }

    // === panedrone: <input type="number" is buggy:
    //      it allows typing not numerical strings + "onChange" is not triggered while such typing

    // return (
    //     <label>
    //         <input type="number" min="1" max="10" pattern="[0-9\s]" value={value)} onChange={handleChange}/>
    //     </label>
    // )

    return (
        <label>
            <input pattern="[0-9\s]" value={value} onChange={handleChange}/>
        </label>
    )
}

const TextAreaField = ({onChange, saveUpdater}) => {

    const [value, setValue] = React.useState("")

    if (saveUpdater) {
        saveUpdater(setValue)
    }

    function handleChange(event) {
        let target = event.target.value
        if (onChange) {
            onChange(target)
        }
        setValue(target);
    }

    return (
        <label>
            <textarea cols="40" rows="10" value={value} onChange={handleChange}></textarea>
        </label>
    )
}

// Project List Panel =============================================================

let newProjectName

const fieldNewProjectName = <StringField onChange={v => newProjectName = v}/>

render(fieldNewProjectName, 'newProjectName')
render(<ProjectCreateButton/>, 'projectCreate')

// Current Project including Tasks ================================================

let currentProject = {
    "p_id": 0,
    "p_name": "",
    "p_tasks_count": 0
}

let updateCurrentProjectName

const fieldCurrentProjectName = <StringField onChange={v => {
    currentProject.p_name = v
}} saveUpdater={(updater) => {
    updateCurrentProjectName = updater
}}/>

let newTaskSubject

const fieldNewTaskSubject = <StringField onChange={v => newTaskSubject = v}/>

render(<ProjectButtons/>, 'projectActions')
render(fieldCurrentProjectName, 'currentProjectName') // after "ProjectButtons"!

render(fieldNewTaskSubject, 'newTaskSubject')
render(<TaskCreateButton/>, 'taskCreate')

// Current Task ===================================================================

let currentTask = {
    "t_id": 0,
    "p_id": 0,
    "t_priority": 0,
    "t_date": "2024-02-12 03:34:16",
    "t_subject": "",
    "t_comments": ""
}

let updateSubject, updateDate, updatePriority, updateComments

const fieldSubject = <StringField onChange={v => {
    currentTask.t_subject = v
}} saveUpdater={(updater) => {
    updateSubject = updater
}}/>

const fieldDate = <StringField onChange={v => {
    currentTask.t_date = v
}} saveUpdater={(updater) => {
    updateDate = updater
}}/>

const fieldPriority = <IntegerField onChange={v => {
    currentTask.t_priority = v
}} saveUpdater={(updater) => {
    updatePriority = updater
}}/>

const areaComments = <TextAreaField onChange={v => {
    currentTask.t_comments = v
}} saveUpdater={(updater) => {
    updateComments = updater
}}/>

render(fieldSubject, 't_subject')
render(fieldDate, 't_date')
render(fieldPriority, 't_priority')
render(areaComments, 't_comments')

render(<TaskButtons/>, 'taskActions')

// Render "Loader" at the very end: it ensures existence of all dependencies:

const Loader = () => {

    fetchWhoIAm()
    fetchProjects()

    return "";
}

render(<Loader/>, "loader")
