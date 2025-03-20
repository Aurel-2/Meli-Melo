let docsList = [];

async function init() {
    let form = document.getElementById('medical-form');

    try {
        const patientResponse = await fetch('?action=api-get-patient', {
            method: 'GET',
            headers: {'Content-Type': 'application/json'}
        });

        if (!patientResponse.ok) {
            throw new Error(`Erreur lors du chargement des patients: ${patientResponse.status}`);
        }

        const patientText = await patientResponse.text();
        docsList = JSON.parse(patientText);

        const diagPromises = docsList.map(patient => fetchPatientDiagnostics(patient));

        const diagResults = await Promise.all(diagPromises);

        docsList = docsList.map(patient => ({
            id: patient.id_patient,
            name: patient.name,
            age: patient.age,
            diagnosisList: getDiagnosisForPatient(patient.id_patient, diagResults)
        }));

        form.reset();
        stats();
        displayMedicalFile(docsList);

    } catch (error) {
        console.error('Erreur lors de l\'initialisation :', error);
        const stats = document.getElementById('stats');
        stats.innerHTML = `<span>Erreur : ${error.message}</span>`;
    }

    addFilterEventListener();
}

async function fetchPatientDiagnostics(patient) {
    try {
        const response = await fetch(`?action=api-get-diagnostic&id=${patient.id_patient}`, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'}
        });

        if (!response.ok) {
            throw new Error(`Échec de la récupération des diagnostics pour le patient ${patient.id_patient}`);
        }

        const diagText = await response.text();
        const diagnostics = JSON.parse(diagText);

        return {
            id: patient.id_patient,
            diagnostics: formatDiagnostics(diagnostics)
        };

    } catch (err) {
        throw new Error(`Erreur lors de la récupération des diagnostics pour le patient ${patient.id_patient}: ${err.message}`);
    }
}

function formatDiagnostics(diagnostics) {
    if (Array.isArray(diagnostics)) {
        return diagnostics.map(d => ({
            Date: d.date,
            Diagnostic: d.description
        }));
    }

    return diagnostics ? [{
        Date: diagnostics.date,
        Diagnostic: diagnostics.description
    }] : [];
}

function getDiagnosisForPatient(patientId, diagResults) {
    const patientDiagnostics = diagResults.find(result => result.id === patientId);
    return patientDiagnostics ? patientDiagnostics.diagnostics : [];
}

function addFilterEventListener() {
    const filter = document.getElementById("filter");
    filter.addEventListener("keyup", () => {
        const query = filter.value.toLowerCase();
        const filteredDocs = docsList.filter(entry => entry.name.toLowerCase().includes(query));
        displayMedicalFile(filteredDocs);
    });
}

function displayMedicalFile(list) {
    const div = document.getElementById('results');
    div.innerHTML = '';

    list.forEach(medicalFile => {
        const fileDiv = document.createElement('div');
        fileDiv.classList.add('medical-file');
        fileDiv.innerHTML = `
            <h3>${medicalFile.name}</h3>
            <ul>
                <li>ID : ${medicalFile.id}</li>
                <li>Âge : ${medicalFile.age}</li>
                ${medicalFile.diagnosisList.map(diagnosis => `
                <li>Diagnostic du ${diagnosis.Date} : ${diagnosis.Diagnostic}</li>
                `).join('')}
            </ul>
            <div>
                <form method="post" action="../public/index.php?action=add-diagnostic">
                    <h4>Ajouter un nouveau diagnostic</h4><br>
                    <input type="hidden" name="id" value="${medicalFile.id}"> <br>
                    <input name="diag" type="text" placeholder="Ajout d'un diagnostic"><br>
                    <input name="date" type="date"> <br>
                    <div class="submit-diag">
                        <input type="submit" value="Ajouter un diagnostic"><br>
                        <a href="?action=delete&&id=${medicalFile.id}">Supprimer</a>
                    </div>

                </form>
            </div>
        `;
        div.appendChild(fileDiv);
    });
}

function stats() {
    if (docsList.length > 0) {
        const totalAge = docsList.reduce((acc, current) => acc + Number(current.age), 0);
        const averageAge = totalAge / docsList.length;
        const stats = document.getElementById('stats');
        stats.innerHTML = `<span>Nombre de dossiers : ${docsList.length}</span>`;
        stats.innerHTML += `<span> Âge moyen : ${averageAge.toFixed(2)}</span>`;
    } else {
        const stats = document.getElementById('stats');
        stats.innerHTML = '<span>Aucun dossier disponible</span>';
    }
}