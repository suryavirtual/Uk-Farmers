// Make sure the "FM" namespace object exists
if (typeof FM != 'object') {
    FM = new Object();
}   
/**
 * Returns the index of the option with the specified value, or -1 if not found
 *
 * @param   element         The HTML select element to be sorted
 * @param   value           The option value to search for
 */
FM.findOption = function(element, value) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    if(!(value == undefined)) {
        for (var i = 0; i < element.options.length; i++) {
            if (element.options[i].value == value) {
                return i;
            }
        }
    }

    return -1;
}


/**
 * Adds an option to a select element
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to remove the options from
 * @param   label           The label text to appear in the menu option
 * @param   value           The value of the option element to add
 * @param   before          (Optional) The option to add this option before (either a numeric index or a value)
 */
FM.addOption = function(element, label, value, before) {
    if (label == undefined || label == '' || value == undefined || value == '') {
        return false;
    } else {
        if (typeof element == 'string') { element = document.getElementById(element); }

        if ( isNaN(parseInt(before)) ) {
            before = FM.findOption(element, before);
            if(before < 0) {
                before = element.options.length;
            }
        }

        // First, start an array with the new option and copy all options from the
        // "before" option to the end after it
        var options = new Array();
        options.push(new Option(label, value, false, false));
        for (var i = before; i < element.options.length; i++) {
            options.push(element.options[i]);
        }

        // Then put them back, including the new one
        for (var i = 0, j = before; i < options.length; i++, j++) {
            element.options[j] = 
                new Option(options[i].text, 
                           options[i].value, 
                           options[i].defaultSelected, 
                           options[i].selected);
        }
    }
} // addOption()



/**
 * Remove options in a multiple-select element, specified by numerical index
 *
 * After the first argument, put as many arguments as you like
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to remove the options from
 * @param   action          The Action to perform. One of: remove, select, deselect
 * @param   options         (Multiple) Option indexes or values to perform the action on
 */
FM.alterOptions = function(element, action) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    if (action == undefined || action != 'remove' && action != 'select' && action != 'deselect') {
        return false;
    } else if (arguments.length > 1) {
        // Put the indexes into an associative array
        var indexes = new Object();
        var values = new Object();
        for (var i = 2; i < arguments.length; i++) {
            if (isNaN(arguments[i])) {
                values['value_' + arguments[i]] = true;
            } else {
                indexes['index_' + arguments[i]] = true;
            }
        }

        // Step through the options backwards and alter them if they are in the list
        for (var i = element.options.length - 1; i >= 0; i--) {
            if (indexes.hasOwnProperty('index_' + i) || values.hasOwnProperty('value_' + element.options[i].value)) {
                switch (action) {
                    case 'remove':
                        element.options[i] = null;
                        break;
                    case 'select':
                        element.options[i].selected = true;
                        break;
                    case 'deselect':
                        element.options[i].selected = false;
                        break;
                }
            }
        }
    }
}  // alterOptions()


/**
 * Swaps two options in an HTML select element
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element with the options to me moved
 * @param   index1          First index number of the options array to swap
 * @param   index2          Second index number of the options array to swap
 */
FM.swapOptions = function(element, index1, index2) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure the indexes are valid
    if (index1 != index2 &&
        index1 >= 0 && index1 < element.options.length &&
        index2 >= 0 && index2 < element.options.length) {

        // Save the selection state of all of the options because Opera
        // seems to forget them when we click the button
        var optionStates = new Array();
        for(i = 0; i < element.options.length; i++) {
            optionStates[i] = element.options[i].selected;
        }

        // Save the first option into a temporary variable
        var option = element.options[index1];

        // Copy the second option into the first option's place
        element.options[index1] =
            new Option(element.options[index2].text,
                       element.options[index2].value,
                       element.options[index2].defaultSelected,
                       element.options[index2].selected);

        // Copy the first option into the second option's place
        element.options[index2] = 
            new Option(option.text,
                       option.value,
                       option.defaultSelected,
                       option.selected);

        // Reset the selection states for Opera's benefit
        for(i = 0; i < element.options.length; i++) {
            element.options[i].selected = optionStates[i];
        }

        // Then select the ones we swapped, if they were selected before the swap
        element.options[index1].selected = optionStates[index2];
        element.options[index2].selected = optionStates[index1];
    }
} // swapOptions()


/**
 * Shifts an option (specified by index or value) in a select element up one position
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The form select element with the options to me shifted
 * @param   option          The index or value of the option to be shifted up
 * @see     swapOptions
 */
FM.shiftOptionUp = function(element, option) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    if (isNaN(option)) option = FM.findOption(element, option);

    // Only move it up if it's not the first option and it's not
    // below another selected option
    if (!isNaN(option) && option > 0 && option < element.options.length) {
        FM.swapOptions(element, option, option - 1);
    }
} // shiftOptionUp()


/**
 * Shifts an option (specified by index or value) in a select element down one position
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The form select element with the options to me shifted
 * @param   option          The index or value of the option to be shifted up
 * @see     swapOptions
 */
FM.shiftOptionDown = function(element, option) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    if (isNaN(option)) option = FM.findOption(element, option);

    // Only move it up if it's not the first option and it's not
    // below another selected option
    if (!isNaN(option) && option > 0 && option < (element.options.length - 1)) {
        FM.swapOptions(element, option, option + 1);
    }
} // shiftOptionDown()


/**
 * Sorts all of the options in a select element
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to be sorted
 * @param   direction       The sort direction: 'asc' or 'desc' (optional)
 */
FM.sortOptions = function(element, direction) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // We have to put the whole options array into a new array, because
    // the options array doesn't support all of the Array methods (like sort)
    // Doesn't that suck?
    options = new Array();
    for (var i = 0; i < element.options.length; i++) {
        options.push(element.options[i]);
    }

    // Sort it with a function that uses the 'text' property of the Option object
    options.sort( function(a, b) {
            if (a.text.toLowerCase() < b.text.toLowerCase()) return -1;
            if (a.text.toLowerCase() > b.text.toLowerCase()) return 1;
            return 0;
        });

    // If asked to sort in descending, reverse it
    if(direction != undefined && direction.toLowerCase() == 'desc') {
        options.reverse();
    }

    // Now copy the array back into the options array
    for (var i = 0; i < options.length; i++) {
        element.options[i] = 
            new Option(options[i].text, 
                       options[i].value, 
                       options[i].defaultSelected, 
                       options[i].selected);
    }
} // sortOptions()


/**
 * Selects all of the options in a multiple-select element
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to make the selection in
 */
FM.selectAllOptions = function(element) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Then loop through the element's options and select all that
    // have keys in the new options associative array
    for (var i = 0; i < element.options.length; i++) {
        element.options[i].selected = true;
    }
}  // selectAllOptions()


/**
 * Deselects all of the options in a multiple-select element
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to make the selection in
 */
FM.deselectAllOptions = function(element) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Then loop through the element's options and select all that
    // have keys in the new options associative array
    for (var i = 0; i < element.options.length; i++) {
        element.options[i].selected = false;
    }
}  // deselectAllOptions()


/**
 * Shifts selected options in a multiple select element up one position
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The form select element with the options to me moved
 * @see     swapOptions
 */
FM.shiftSelectionsUp = function(element) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure it's a multiple select element
    if (element.type == 'select-multiple') {
        // Step through the options and move the selected ones
        for (var i = 0; i < element.options.length; i++) {
            // Is the option selected?
            if (element.options[i].selected) {
                // Only swap it if it's not the first option and it's not
                // below another selected option
                if (i > 0 && element.options[i - 1].selected != true) {
                    FM.swapOptions(element, i, i - 1);
                }
            }
        } // for
    }
} // shiftSelectionsUp()


/**
 * Shifts selected options in a multiple select element down one position
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element with the options to me moved
 * @see     swapOptions
 */
FM.shiftSelectionsDown = function(element) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure it's a multiple select element
    if (element.type == 'select-multiple') {
        // Step through the options and move the selected ones
        for (var i = element.options.length - 1; i >= 0 ; i--) {
            // Is the option selected?
            if (element.options[i].selected) {
                // Only swap it if it's not the last option and it's not
                // above another selected option
                if (i < (element.options.length - 1) && !element.options[i + 1].selected) {
                    FM.swapOptions(element, i, i + 1);
                }
            }
        } // for
    }
} // shiftSelectionsDown()


/**
 * Removes selected options from a select element
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to remove the options from
 */
FM.removeSelections = function(element) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    if (element.type == 'select-multiple') {
        for (var i = element.options.length - 1; i >= 0; i--) {
            if (element.options[i].selected) {
                // Delete it from the source element
                element.options[i] = null;
            }
        }
    } else {
        element.options[element.selectedIndex] = null;
    }
} // removeSelections()


/**
 * Copies selected options in a multiple-select element to another multiple-select element.
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   fromElement     The HTML select element with the options to be copied
 * @param   toElement       The HTML select element to copy the options to
 */
FM.copySelections = function(fromElement, toElement) {
    if (typeof fromElement == 'string') fromElement = document.getElementById(fromElement);
    if (typeof toElement == 'string') toElement = document.getElementById(toElement);

    // Make sure both parameters are multiple select element
    if (fromElement.type == 'select-multiple' && toElement.type == 'select-multiple') {
        // Step through the options and move the selected ones
        for (var i = 0; i < fromElement.options.length; i++) {
            // Is the option selected?
            if (fromElement.options[i].selected) {
                // Copy it to the end of the destination element
                toElement.options[toElement.options.length] = 
                    new Option(fromElement.options[i].text, 
                               fromElement.options[i].value, 
                               fromElement.options[i].defaultSelected, 
                               fromElement.options[i].selected);
            }
        } // for
    }
} // copySelections()


/**
 * Moves selected options in a multiple-select element to another multiple-select element.
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   fromElement     The HTML select element with the options to be moved
 * @param   toElement       The HTML select element to move the options to
 * @see     copySelections
 * @see     removeSelections
 */
FM.moveSelections = function(fromElement, toElement) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure both parameters are multiple select elements
    if (fromElement.type == 'select-multiple' && toElement.type == 'select-multiple') {
        FM.copySelections(fromElement, toElement);
        FM.removeSelections(fromElement);
    }
} // moveSelections()

FM.addSelections = function() {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure both parameters are multiple select elements
    if (fromElement.type == 'select-multiple' && toElement.type == 'select-multiple') {
        FM.copySelections(document.forms['BoxForm'].getElementById('Box2'), document.forms['BoxForm'].getElementById('Box1'));
        FM.removeSelections(fromElement);
    }
} 

FM.deleteSelections = function() {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure both parameters are multiple select elements
    if (fromElement.type == 'select-multiple' && toElement.type == 'select-multiple') {
        FM.copySelections(document.forms['BoxForm'].getElementById('Box1'), document.forms['BoxForm'].getElementById('Box2'));
        FM.removeSelections(fromElement);
    }
} 