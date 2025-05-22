import { createTheme } from "@mui/material/styles";
export const orangeColor = "#fb2105";
export const darkOrangeColor = "#68100d";
export const darkRedColor = "#5e0c10";
export const whiteColor = "#f5f5f5";
export const textColor = "#131313";

export const grayColor = "#999999";
export const backgroundDark = "#1a1a1a";
export const surfaceLight = "#eaeaea";
export const accentCool = "#1e2a38";
export const accentGreen = "#4b6043";

export const lightTheme = createTheme({
  palette: {
    mode: "light",
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
    mode: "dark",
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

