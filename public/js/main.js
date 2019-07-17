
const companies = document.getElementById('companies');
const employees = document.getElementById('employees');

if (companies){
  companies.addEventListener('click', (e)=>{
    if(e.target.className === 'btn btn-danger delete-company'){
      if(confirm('Are you sure?\nThis action will delete also the employees')){
        const id = e.target.getAttribute('data-id');

        fetch(`/companies/delete/${id}`,{method: 'DELETE'}).then(res=> window.location.reload());

      }
    }
  });
}

if(employees){
  employees.addEventListener('click', (e)=>{
    if(e.target.className === 'btn btn-danger delete-employee'){
      if(confirm('Are you sure?')){
        const id = e.target.getAttribute('data-id');

        fetch(`/employees/delete/${id}`,{method: 'DELETE'}).then(res=> window.location.reload());

      }
    }
  });
}
