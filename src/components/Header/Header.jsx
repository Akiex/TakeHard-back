import React from "react";
import Navbar from "./../Navbar/Navbar";
import styles from "./Header.module.scss";

const Header = () => (
  <header className={styles.header}>
    <div className={styles.wrapper}>
      <h1 className={styles.title}>
        <a href="/">Take Hardvantage</a>
      </h1>
      <Navbar />
    </div>
  </header>
);

export default Header;
