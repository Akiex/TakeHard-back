import React, { useEffect, useState, useMemo } from "react";
import { BrowserRouter } from "react-router-dom";
import AppRoutes from "./routes/Router";
import Header from "./components/Header/Header";
import Footer from "./components/Footer/Footer";
import "./styles/global.scss";
import { AuthProvider } from "./context/AuthContext";
import { ThemeProvider, CssBaseline } from '@mui/material';
import { lightTheme, darkTheme } from './utils/theme';

const App = () => {
  const [darkMode, setDarkMode] = useState(false);
  const theme = useMemo(() => (darkMode ? darkTheme : lightTheme), [darkMode]);
  useEffect(() => {
    document.body.classList.toggle("dark-mode", darkMode);
  }, [darkMode]);

  return (
    <AuthProvider>
      <ThemeProvider theme={theme}>
      <BrowserRouter>
        <Header />
        <button
          className="dark-toggle"
          onClick={() => setDarkMode(!darkMode)}
        >
          {darkMode ? "☀️" : "🌙"}
        </button>
        <main>
          <AppRoutes />
        </main>
        <Footer />
      </BrowserRouter>
      </ThemeProvider>
    </AuthProvider>
  );
};

export default App;
