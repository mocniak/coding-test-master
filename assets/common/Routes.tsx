import React from "react";
import { Route } from "react-router-dom";
import Login from "../security/Login";
import Home from "./Home";
import Classes from "../classes/Classes";
import { Box } from "@material-ui/core";

const Routes: React.FC = () => (
    <Box style={{ margin: 16 }}>
        <Route exact path="/" component={Home} />
        <Route exact path="/login" component={Login} />
        <Route exact path="/classes" component={Classes} />
    </Box>
);

export default Routes;
