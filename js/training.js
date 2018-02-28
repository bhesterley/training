function chkMark(chkElement) {
   var edited = document.getElementById("edited");
   if (edited.innerText == "0") {
	  edited.innerText = "1";
	  document.getElementById("selectgroup-span").innerHTML += "<span style='font-size:14pt;color:red;'>*</span>";
     document.getElementById("copyicon").hidden = true;
   }
   if (chkElement.id != "selectall") {
      var id = chkElement.id.split("-")[1];
      if (chkElement.checked) {
         document.getElementById("row-" + id).style.fontWeight = "bold";
      } else {
      document.getElementById("row-" + id).style.fontWeight = "normal";
	  document.getElementById("selectall").checked = false;
      }
   }
}
function selectAllClicked() {
	var checks = document.getElementsByTagName("input");
	var isChecked = (document.getElementById("selectall").checked ? true : false);
	for (var i = 0; i < checks.length; i++) {
		if (checks[i].type == "checkbox") {
			checks[i].checked = isChecked;
			chkMark(checks[i]);
		}
	}
}
function copyGroups() {
	var fromId = document.getElementById("SelectGroup").value;
	var toGroup = document.getElementById("CopyGroup");
	var toId = toGroup.value;
	var toText = toGroup.options[toGroup.selectedIndex].text;
	if (confirm("Are you sure you want to overwrite the assignments for '" + toText + "'?")) {
		window.location.href="copy-groups.php?from=" + fromId + "&to=" + toId;
	}
}
function selectChange() {
	var edited = document.getElementById('edited').innerText;
	var e = document.getElementById('SelectGroup'); 
	if (edited == '1') {
		if (confirm('You have unsaved changes.  Are you sure you want to navigate away from this page?')) {
			document.location.href = window.location.href.split('?')[0] + '?id=' + e.options[e.selectedIndex].value;
		} else {
			var i = document.getElementById('groupindex').innerText;
			e.options[i].selected = true;
		}
	} else {
		document.location.href = window.location.href.split('?')[0] + '?id=' + e.options[e.selectedIndex].value;
	}
}

document.getElementById('groupindex').innerText = document.getElementById('SelectGroup').selectedIndex;