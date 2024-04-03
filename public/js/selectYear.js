function saveSelectedYear()
{
	let e = document.getElementById("selectYear");
	let text = e.options[e.selectedIndex].text;
	//console.log(text)

	let xhr = new XMLHttpRequest(); // AJAX Request to server 
	xhr.open("POST", "saveSelectedOption.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function()
	{
		if (xhr.readyState === 4 && xhr.status === 200)
			console.log(xhr.responseText);
	};
	xhr.send("year=" + encodeURIComponent(text));
}