import React from 'react';
import axios from 'axios';

function App() {
    let res = null;
    axios.get('/api/customers')
        .then(response => {
            console.log('Customer data:', response.data);
            res = response;
        })
        .catch(error => {
            console.error('Error fetching customers:', error);
        });


    return (
        <div>
            {res}
        </div>
    );
}

export default App;
