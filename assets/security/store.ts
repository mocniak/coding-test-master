import { createAsyncThunk, createSlice } from "@reduxjs/toolkit";
import { LoginRequest, User } from "./model";
import { RootState } from "../common/store";
import axios from "axios";

interface State {
    user?: User;
    error?: string;
}

const initialState: State = {};

export const login = createAsyncThunk(
    "security/login",
    async (payload: LoginRequest, thunkApi) => {
        try {
            const response = await axios.post<User>("/auth/login", payload);
            return response.data;
        } catch (error) {
            return thunkApi.rejectWithValue(error.response.data.error);
        }
    }
);

export const fetchUser = createAsyncThunk("auth/user", async () => {
    const response = await axios.get<User>("/auth/user");
    return response.data;
});

const slice = createSlice({
    name: "security",
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(fetchUser.fulfilled, (state, { payload }) => {
                state.user = payload;
            })
            .addCase(login.fulfilled, (state, { payload }) => {
                state.user = payload;
            })
            .addCase(login.rejected, (state, { payload }) => {
                state.error = payload as string;
            });
    },
});

export const securityReducer = slice.reducer;

export const getUser = (state: RootState) => state.security.user;
export const getError = (state: RootState) => state.security.error;
