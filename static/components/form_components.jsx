// import * as React from "react";

// export
const StringField = ({onChange, saveUpdater}) => {

    const refDom = React.useRef()

    function setValue(value) {
        refDom.current.value = value
    }

    if (saveUpdater) {
        saveUpdater((value) => setValue(value))
    }

    function handleChange(event) {
        let value = event.target.value
        if (onChange) {
            onChange(value)
        }
        setValue(value)
    }

    return (
        <label>
            <input type="text" ref={refDom} onChange={handleChange}/>
        </label>
    )
}

// export
const IntegerField = ({onChange, saveUpdater, min = 1, max = 10}) => {

    const refDom = React.useRef()
    const refValue = React.useRef(min)

    function setValue(value) {
        let parsed = parseInt(value.toString())
        if (parsed < min || parsed > max) {
            parsed = refValue.current // === panedrone: reset to the old one
        } else {
            refValue.current = parsed
        }
        refDom.current.value = parsed.toString()
    }

    if (saveUpdater) {
        saveUpdater(setValue)
    }

    function handleChange(event) {
        let value = event.target.value
        if (!value) {
            return // === panedrone: allow typing from scratch
        }
        setValue(value);
    }

    function handleUp() {
        let current = refValue.current
        if (current >= max) {
            return
        }
        current = current + 1
        if (onChange) {
            onChange(current.toString())
        }
        setValue(current);
    }

    function handleDown() {
        let current = refValue.current
        if (current <= min) {
            return
        }
        current = current - 1
        if (onChange) {
            onChange(current.toString())
        }
        setValue(current);
    }

    // === panedrone: <input type="number" is buggy:
    //      - impossible to disable typing of not-numbers
    //      - with invalid values, "onChange" is not fired

    // return (
    //     <label>
    //         <input type="number" min="1" max="10" pattern="[0-9\s]" value={value)} onChange={handleChange}/>
    //     </label>
    // )

    return (
        <div style={{display: "flex"}}>
            <input type="text" min={min} max={max} pattern="[0-9\s]"
                   ref={refDom} placeholder="Type a number..." onChange={handleChange} style={{flex: 1}}/>
            <div>
                <button style={{padding: 0, margin: 1, display: "block", lineHeight: "0.7em"}} onClick={handleUp}>
                    &#x25B4;
                </button>
                <button style={{padding: 0, margin: 1, display: "block", lineHeight: "0.7em"}} onClick={handleDown}>
                    &#x25BE;
                </button>
            </div>
        </div>
    )
}

// export
const TextAreaField = ({onChange, saveUpdater}) => {

    const refTextArea = React.useRef()

    function setValue(value) {
        refTextArea.current.value = value
    }

    if (saveUpdater) {
        saveUpdater((value) => setValue(value))
    }

    function handleChange(event) {
        let value = event.target.value
        if (onChange) {
            onChange(value)
        }
        setValue(value)
    }

    return (
        <label>
            <textarea cols="40" rows="10" ref={refTextArea} onChange={handleChange}></textarea>
        </label>
    )
}
