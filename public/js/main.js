
const companies = document.getElementById('companies');

if (companies){
  companies.addEventListener('click', (e)=>{
    if(e.target.className === 'btn btn-danger delete-company'){
      const name = e.target.getAttribute('data-id');

      fetch(`companies/delete/${name}`,{method: 'DELETE'}).then(res=> window.location.reload());
      // if(confirm('Are you sure')){
      // }
    }
  });
}
