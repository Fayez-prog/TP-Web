document.getElementById('csvFile').addEventListener('change', handleFileSelect, false);

document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.createElement('button');
    saveButton.textContent = 'Exporter en CSV';
    saveButton.addEventListener('click', exportCSV);
    document.body.appendChild(saveButton);
});

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        Papa.parse(file, {
            complete: function (results) {
                createForm(results.data);
            },
            header: true
        });
    }
}

function createForm(data) {
    const formContainer = document.getElementById('formContainer');
    formContainer.innerHTML = ''; // Clear any existing form

    data.forEach((row, rowIndex) => {
        const formRow = document.createElement('div');
        formRow.classList.add('form-row');

        createFormField(formRow, `nom-${rowIndex}`, 'Nom', row.nom);
        createFormField(formRow, `prenom-${rowIndex}`, 'Prenom', row.prenom);
        createFormField(formRow, `note1-${rowIndex}`, 'Note 1', row.note1, false, rowIndex);
        createFormField(formRow, `note2-${rowIndex}`, 'Note 2', row.note2, false, rowIndex);
        createFormField(formRow, `moyenne-${rowIndex}`, 'Moyenne', calculateAverage(row.note1, row.note2), true);

        formContainer.appendChild(formRow);
    });
}

function createFormField(container, id, labelText, value, readonly = false, rowIndex = null) {
    const label = document.createElement('label');
    label.setAttribute('for', id);
    label.textContent = labelText;

    const input = document.createElement('input');
    input.setAttribute('type', 'text');
    input.setAttribute('id', id);
    input.setAttribute('name', labelText.toLowerCase());
    input.setAttribute('value', value || '');

    if (readonly) {
        input.setAttribute('readonly', 'readonly');
    } else if (labelText.includes('Note')) {
        input.addEventListener('input', () => validateAndUpdateAverage(rowIndex));
    }

    container.appendChild(label);
    container.appendChild(input);
}

function calculateAverage(note1, note2) {
    const n1 = parseFloat(note1);
    const n2 = parseFloat(note2);
    if (isNaN(n1) || isNaN(n2)) return '';
    return ((n1 + n2) / 2).toFixed(2);
}

function validateAndUpdateAverage(rowIndex) {
    const note1 = document.getElementById(`note1-${rowIndex}`).value;
    const note2 = document.getElementById(`note2-${rowIndex}`).value;

    if (!note1.trim() || !note2.trim() || !isValidNote(note1) || !isValidNote(note2)) {
        document.getElementById(`moyenne-${rowIndex}`).value = '';
        return;
    }

    const moyenneField = document.getElementById(`moyenne-${rowIndex}`);
    moyenneField.value = calculateAverage(note1, note2);
}

function isValidNote(note) {
    const num = parseFloat(note);
    return !isNaN(num) && num >= 0 && num <= 20;
}

function exportCSV() {
    const rows = [];
    document.querySelectorAll('.form-row').forEach((row, index) => {
        const nom = document.getElementById(`nom-${index}`).value;
        const prenom = document.getElementById(`prenom-${index}`).value;
        const note1 = document.getElementById(`note1-${index}`).value;
        const note2 = document.getElementById(`note2-${index}`).value;
        const moyenne = document.getElementById(`moyenne-${index}`).value;
        rows.push([nom, prenom, note1, note2, moyenne]);
    });

    const csvContent = 'Nom,Prenom,Note 1,Note 2,Moyenne\n' + rows.map(e => e.join(",")).join("\n");
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'eleves_modifie.csv';
    link.click();
}