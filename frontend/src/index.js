import React from 'react';
import ReactDOM from 'react-dom';
import ShopModal from './components/ShopModal';

const el = document.getElementById('root');
const root = ReactDOM.createRoot(el);

function App() {
    return (
        <>
            <ShopModal />
        </>
    );
}

root.render(<App />)