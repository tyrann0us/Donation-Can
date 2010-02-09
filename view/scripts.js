/*
Copyright (c) 2009-2010, Jarkko Laine.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Adds a new field for entering a donation sum.
 */
function addFormTextField(countElementId, parentId, elementNameBody) {
	var countElement = document.getElementById(countElementId);
	var count = parseInt(countElement.value);
	var parent = document.getElementById(parentId);
	
	// Create a div to contain the input, remove link and line break
	var groupElement = document.createElement("div");
	groupElement.setAttribute("id", elementNameBody + count);
	
	var element = document.createElement("input");
	element.setAttribute("name", elementNameBody + count);
	element.setAttribute("class", "regular-text");
	element.setAttribute("type", "text");
	element.setAttribute("size", 40);

	var removeElement = document.createElement('a');
	removeElement.appendChild(document.createTextNode("Remove"));
	removeElement.setAttribute("onclick", "return removeFormTextField('" + parentId + "', '" + elementNameBody + count + "');");
	removeElement.setAttribute("href", "#");
	
	groupElement.appendChild(element);
	groupElement.appendChild(removeElement);
	
	parent.appendChild(groupElement);
		
	countElement.value = (count + 1);
		
	return false;
}

function removeFormTextField(parentId, elementId) {
	var element = document.getElementById(elementId);
	var parent = document.getElementById(parentId);
	
	if (parent != null) {
		parent.removeChild(element);
	}
	
	return false;
}