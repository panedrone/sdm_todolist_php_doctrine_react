// import * as whoiam from './components/whoiam'
// import * as project_list from './components/project_list'
// import * as project_details from './components/project_details'
// import * as task_details from './components/task_details'
// import * as api from './components/api'

project_list.renderComponents()
project_details.renderComponents()
task_details.renderComponents()
//
api.renderComponents()

// Render "Loader" at the very end: it ensures existence of all dependencies:

// const Loader = () => {
//
//     // direct call of windowOnLoad() may cause something like:
//     //
//     // Warning: Cannot update a component (`Wait`) while rendering a different component (`Loader`). To locate the bad setState() call inside `Loader`...
//     //
//     // === panedrone: cannot call "setState()" of other component inside function "Loader"
//     //
//     // windowOnLoad() === wrong!
//
//     React.useEffect(() => windowOnLoad());
//
//     return "";
// }
//
// shared.render(<Loader/>, "loader")

async function windowOnLoad() {
    whoiam.fetchWhoIAm()
    project_list.fetchProjects()
}

windowOnLoad().then(() => console.log('== windowOnLoad() completed =='))