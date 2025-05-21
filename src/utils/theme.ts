import { createTheme } from '@mui/material/styles';
export const orangeColor      = '#fb2105';  // Accent principal
export const darkOrangeColor  = '#68100d';  // Hover / active
export const darkRedColor     = '#5e0c10';  // Danger / alertes fortes
export const whiteColor       = '#f5f5f5';  // Fond principal
export const textColor        = '#131313';  // Texte principal

export const grayColor        = '#999999';  // Texte secondaire
export const backgroundDark   = '#1a1a1a';  // Fond sombre
export const surfaceLight     = '#eaeaea';  // Séparateurs / surfaces légères
export const accentCool       = '#1e2a38';  // Accent secondaire froid
export const accentGreen      = '#4b6043';  // Accent alternatif doux// adapte le chemin

export const lightTheme = createTheme({
  palette: {
    mode: 'light',
    background: {
      default: whiteColor,
      paper: whiteColor,
    },
    primary: {
      main: orangeColor,
      dark: darkOrangeColor,
    },
    error: {
      main: darkRedColor,
    },
    text: {
      primary: textColor,
      secondary: grayColor,
    },
    divider: surfaceLight,
  },
});

export const darkTheme = createTheme({
  palette: {
    mode: 'dark',
    background: {
      default: backgroundDark,
      paper: backgroundDark,
    },
    primary: {
      main: orangeColor,
      dark: darkOrangeColor,
    },
    error: {
      main: darkRedColor,
    },
    text: {
      primary: whiteColor,
      secondary: grayColor,
    },
    divider: surfaceLight,
  },
});
