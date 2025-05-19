import React from "react";
import { BrowserRouter } from "react-router-dom";
import AppRoutes from "./routes/Router";
import Header from "./components/Header/Header";
import Footer from "./components/Footer/Footer";

import "./styles/global.scss";
import { AuthProvider } from "./context/AuthContext";
const App = () => (
  <AuthProvider>
  <BrowserRouter>
    <Header />
      <main>
        <AppRoutes />
      </main>
    <Footer />
  </BrowserRouter>
  </AuthProvider>
);

export default App;
