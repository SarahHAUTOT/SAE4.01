// Import dependencies
const { insertEtudiant, insertCompetence, insertModule, insertMoyenne } = require('DB.js');
const XLSX = require('xlsx');

const spreadSheet = XLSX.readFile('test.xlsx');
let colums = {};
for (const columName of spreadSheet.SheetNames) 
{
	colums[columName] = XLSX.utils.sheet_to_json(spreadSheet.Sheets[columName]);
}

console.log(JSON.stringify(colums.Sheet1), "\n\n");

let fileMoy = document.getElementById('fichier_import');
fileMoy.addEventListener('change', () =>
{
	const file = fileMoy.files[0];
	const reader = new FileReader();

	reader.onload = (event) => 
	{
		const data = event.target.result;
		const workbook = XLSX.read(data, {type:'array'});
		const sheet = workbook.SheetNames[0];
		const worksheet = workbook.Sheets[sheet];
		const rows = XLSX.utils.sheet_to_json(worksheet);

		for (let row of rows)
		{
			let etudiant = 
			{
				id : row['etudid'],
				civ: row['Civ.'],
				nom: row['Nom'],
				td : row['TD'],
				tp : row['TP'],
				bac: row['Bac'],
				bonus : row[15],
				prenom: row['Prénom'],
				parcours: row['Cursus'],
			};

			insertEtudiant(etudiant)
			.then(insertedData => {
				console.log('Inserted data:', insertedData);
			})
			.catch(error => {
				console.error('Error:', error);
			});

			let module = 
			{
				//TODO 
			};
			
			insertModule(module)
			.then(insertedData => {
				console.log('Inserted data:', insertedData);
			})
			.catch(error => {
				console.error('Error:', error);
			});

			let competence = 
			{
				//TODO 
			};
			
			insertCompetence(competence)
			.then(insertedData => {
				console.log('Inserted data:', insertedData);
			})
			.catch(error => {
				console.error('Error:', error);
			});

			let moyenne = 
			{
				//TODO 
			};
			
			insertMoyenne(moyenne)
			.then(insertedData => {
				console.log('Inserted data:', insertedData);
			})
			.catch(error => {
				console.error('Error:', error);
			});

		}
	}
});