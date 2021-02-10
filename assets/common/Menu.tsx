import { NavLink } from "react-router-dom";
import React from "react";
import { useDispatch, useSelector } from "./store";
import { fetchUser, getUser } from "../security/store";
import { useEffect } from "react";
import { AppBar, Button, Toolbar } from "@material-ui/core";

const Menu: React.FC = () => {
    const dispatch = useDispatch();
    const user = useSelector(getUser);

    useEffect(() => {
        if (user !== undefined) {
            return;
        }

        void dispatch(fetchUser());
    }, [dispatch, user]);

    return (
        <AppBar position="static">
            <Toolbar>
                <Button color="inherit" component={NavLink} to="/">
                    Home
                </Button>
                {!user && (
                    <Button color="inherit" component={NavLink} to="/login">
                        Login
                    </Button>
                )}
                {user && (
                    <>
                        <Button
                            color="inherit"
                            component={NavLink}
                            to="/classes"
                        >
                            Classes
                        </Button>
                        <Button
                            color="inherit"
                            component="a"
                            href="/auth/logout"
                        >
                            Logout
                        </Button>
                    </>
                )}
            </Toolbar>
        </AppBar>
    );
};

export default Menu;
