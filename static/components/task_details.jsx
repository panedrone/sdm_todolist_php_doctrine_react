// import * as React from "react";
//
// import * as shared from "./shared";
// import {ErrorArea} from "./error_area";
// import {IntegerField, StringField, TextAreaField} from "./form_components";
// import fire from './event_bus.jsx'
// import * as api from "./api"

let _updateSubject = (_) => {
}
let _updateDate = (_) => {
}
let _updatePriority = (_) => {
}
let _updateComments = (_) => {
}

let _currentTask = {
    "t_id": 0,
    "p_id": 0,
    "t_priority": 0,
    "t_date": "2024-02-12 03:34:16",
    "t_subject": "",
    "t_comments": ""
}

const TaskTitle = ({initial}) => {
    return <span>{initial}</span>
}

fire.fetchTask = (t_id) => {
    api.getJson(`api/tasks/${t_id}`, (json) => {
        fire.setVisibleTaskForm(true)
        _currentTask = json
        if (!_currentTask) {
            fire.showServerError("failed to get task data")
            return
        }
        shared.render(<TaskTitle initial={_currentTask.t_subject}/>, 'taskTitle')
        _updateSubject(_currentTask.t_subject)
        _updateDate(_currentTask.t_date)
        _updatePriority(_currentTask.t_priority)
        _updateComments(_currentTask.t_comments)
    })
}

const TaskButtons = () => {

    function _taskUpdate() {
        if (!isNaN(_currentTask.t_priority)) {
            _currentTask.t_priority = parseInt(_currentTask.t_priority.toString());
            if (!_currentTask.t_priority) {
                _currentTask.t_priority = 1
            }
        }
        let json = JSON.stringify(_currentTask)
        let t_id = _currentTask.t_id
        api.putJson(`api/tasks/${t_id}`, json, async (resp) => {
            if (resp.status === 200) {
                fire.fetchProjectTasks(_currentTask.p_id);
                fire.fetchTask(_currentTask.t_id);
                _hideTaskError()
                return
            }
            await _showTaskError(resp)
        })
    }

    function _taskDelete() {
        let t_id = _currentTask.t_id
        api.delete204(`api/tasks/${t_id}`, () => {
            fire.fetchProjects(); // update tasks count
            fire.fetchProjectTasks(_currentTask.p_id);
            fire.setVisibleTaskForm(false)
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
                        <input type="button" value="&#x2713;" onClick={() => _taskUpdate()}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="x" onClick={() => _taskDelete()}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&lt;" onClick={() => fire.setVisibleTaskForm(false)}/>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    )
}

function _hideTaskError() {
    _updateTaskError("")
}

async function _showTaskError(resp) {
    let msg = await resp.text()
    msg = api.unicodeToChar(msg);
    // https://stackoverflow.com/questions/6640382/how-to-remove-backslash-escaping-from-a-javascript-var
    msg = msg.replace(/\\\\"/g, '"');
    msg = msg.replace(/\\"/g, '"');
    msg = resp.status.toString() + " ==> " + msg
    _updateTaskError(msg)
}

const _fieldSubject = <StringField onChange={v => {
    _currentTask.t_subject = v
}} saveUpdater={(updater) => {
    _updateSubject = updater
}}/>

const _fieldDate = <StringField onChange={v => {
    _currentTask.t_date = v
}} saveUpdater={(updater) => {
    _updateDate = updater
}}/>

const _fieldPriority = <IntegerField onChange={v => {
    _currentTask.t_priority = v
}} saveUpdater={(updater) => {
    _updatePriority = updater
}}/>

const _fieldComments = <TextAreaField onChange={v => {
    _currentTask.t_comments = v
}} saveUpdater={(updater) => {
    _updateComments = updater
}}/>

let _updateTaskError = (_) => {
}

const _taskError = <ErrorArea saveUpdater={(updater) => _updateTaskError = updater}/>


const task_details = {
    /*  export */
    // function
    renderComponents() {
        shared.render(<TaskButtons/>, 'taskActions')
        shared.render(_fieldSubject, 't_subject')
        shared.render(_fieldDate, 't_date')
        shared.render(_fieldPriority, 't_priority')
        shared.render(_fieldComments, 't_comments')
        shared.render(_taskError, 'taskError');
    }
}