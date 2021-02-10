import { useState } from "react";
import React from "react";
import { useDispatch, useSelector } from "../common/store";
import { getError, getUser, login } from "./store";
import { Redirect } from "react-router-dom";
import {
    Button,
    Card,
    CardContent,
    TextField,
    Typography,
} from "@material-ui/core";

const Login: React.FC = () => {
    const user = useSelector(getUser);
    const error = useSelector(getError);
    const dispatch = useDispatch();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    if (user) {
        return <Redirect to="/" />;
    }

    const handleEmailChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setEmail(event.target.value);
    };

    const handlePasswordChange = (
        event: React.ChangeEvent<HTMLInputElement>
    ) => {
        setPassword(event.target.value);
    };

    const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        await dispatch(login({ email, password }));
    };

    return (
        <Card style={{ maxWidth: 400 }}>
            <CardContent>
                <form onSubmit={handleSubmit}>
                    <div>
                        <TextField
                            variant="outlined"
                            margin="normal"
                            fullWidth
                            type="email"
                            value={email}
                            onChange={handleEmailChange}
                        />
                    </div>
                    <div>
                        <TextField
                            variant="outlined"
                            margin="normal"
                            fullWidth
                            type="password"
                            value={password}
                            onChange={handlePasswordChange}
                        />
                    </div>
                    <Typography color="error">{error}</Typography>
                    <Button variant="contained" color="primary" type="submit">
                        Login
                    </Button>
                </form>
            </CardContent>
        </Card>
    );
};

export default Login;
