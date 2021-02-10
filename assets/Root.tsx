import React from "react";
import Routes from "./common/Routes";
import Menu from "./common/Menu";
import { BrowserRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "./common/store";
import {
    createMuiTheme,
    CssBaseline,
    MuiThemeProvider,
} from "@material-ui/core";

const theme = createMuiTheme({});

const Root: React.FC = () => {
    return (
        <BrowserRouter>
            <Provider store={store}>
                <CssBaseline />
                <MuiThemeProvider theme={theme}>
                    <Menu />
                    <Routes />
                </MuiThemeProvider>
            </Provider>
        </BrowserRouter>
    );
};

export default Root;
